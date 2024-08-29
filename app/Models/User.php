<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\CategoryService;
use BezhanSalleh\FilamentShield\FilamentShield;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        if (config('filament-shield.app_user.enabled', false)) {
            FilamentShield::createRole(name: config('filament-shield.app_user.name', 'app_user'));
            static::created(fn ($user) => $user->assignRole(config('filament-shield.app_user.name', 'app_user')));
            static::deleting(fn ($user) => $user->removeRole(config('filament-shield.app_user.name', 'app_user')));
        }

        static::created(function ($user) {
            $categoriesWithSubcategories = [
                'ALVARÁS, LICENÇAS, PERMISSÕES, PROTOCOLOS, DOCUMENTOS DE CONFORMIDADE LEGAL' => [
                    'Bombeiros',
                    'Ambiental',
                    'Municipal',
                    'Vigilância Sanitária',
                    'Permissões Produtos Químicos Controlados',
                    'Polícia Civil - Permissões Produtos Químicos Controlados',
                    'Polícia Federal - Permissões Produtos Químicos Controlados',
                    'Exército - Permissões Produtos Químicos Controlados',
                    'Outros',
                ],
                'SAÚDE E SEGURANÇA' => [
                    'LTCAT',
                    'PPRA',
                    'Controle de EPIs',
                    'PCMSO',
                    'ASO',
                    'SESMT',
                    'CIPA',
                    'SIPAT',
                    'Brigada de Incêndio/Plano de Emergência',
                    'Caldeiras e Vasos de Pressão',
                    'Registros de Manutenção',
                    'Preventiva - Cronograma de Manutenção',
                    'Corretiva - Cronograma de Manutenção',
                    'Predial - Cronograma de Manutenção',
                    'Elevadores - Cronograma de Manutenção',
                    'Extintores - Cronograma de Manutenção',
                    'Recarga de Extintores - Cronograma de Manutenção',
                    'Hidrantes - Cronograma de Manutenção',
                    'Luzes de Emergência - Cronograma de Manutenção',
                    'Botoeiras de Alarme - Cronograma de Manutenção',
                    'Outros',
                ],
                'MEIO AMBIENTE' => [
                    'Cadastro de Empresas Potencialmente Poluidoras - IBAMA',
                    'Plano de Gerenciamento de Resíduos',
                    'Análise de Emissões Atmosféricas',
                    'Análise de Ruído Periférico',
                    'Outorga para Uso de Água',
                    'Laudo de Potabilidade de Água',
                    'Análise de Efluentes',
                    'Outros',
                ],
                'CERTIFICAÇÕES (se aplicável / opcional)' => [
                    'Políticas e Procedimentos',
                    'ISO 9001',
                    'ISO 14001',
                    'OHSAS 18001',
                    'SA8000',
                    'FSC',
                    'Outros',
                ],
            ];

            (new CategoryService())->createCategoriesAndSubcategories($categoriesWithSubcategories, $user);
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole(config('filament-shield.super_admin.name'));
        } elseif ($panel->getId() === 'app') {
            return $this->hasRole(config('filament-shield.app_user.name')) || $this->hasRole(config('filament-shield.super_admin.name'));
        }
        return false;
    }

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }
}
