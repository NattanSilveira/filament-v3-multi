<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class Subscription extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static string $view = 'filament.app.pages.subscription';

    protected static ?string $navigationGroup = 'Gerenciamento de Assinaturas';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Minha Assinatura';

    //title
    protected static ?string $title = 'Minha Assinatura';

//    public $defaultAction = 'onboarding';
//
//   todo talvez implementar um modal de onboarding caso nao tenha assinatura ativa

//    public function onboardingAction(): Action
//    {
//        return Action::make('onboarding')
//            ->modalHeading('Welcome');
//    }

    public ?array $data = [];

    public bool $hasSubscription = false;

    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'whatsapp' => $this->user->whatsapp,
            'settings' => $this->user->settings,
        ]);

        $this->hasSubscription = $this->userHasSubscription();
    }

    public function userHasSubscription(): bool
    {
        return $this->user->hasSubscription();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Dados Gerais')
                    ->schema([

                        Group::make([
                            Placeholder::make('name')
                                ->label('Nome')
                                ->columnSpan(1),
                            TextInput::make('name')
                                ->required()
                                ->hiddenLabel()
                                ->columnSpan(5),
                        ])->columns(6),

                        Group::make([
                            Placeholder::make('email')
                                ->label('Email')
                                ->columnSpan(1),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->hiddenLabel()
                                ->columnSpan(5),
                        ])->columns(6),

                        Group::make([
                            Placeholder::make('whatsapp')
                                ->label('WhatsApp')
                                ->columnSpan(1),
                            TextInput::make('whatsapp')
                                ->mask('(99) 99999-9999')
                                ->required()
                                ->hiddenLabel()
                                ->columnSpan(5),
                        ])->columns(6),

                        Group::make([
                            Placeholder::make('Idioma')
                                ->label('Idioma')
                                ->columnSpan(1),
                            Select::make('settings.language')
                                ->required()
                                ->options([])
                                ->searchable()
                                ->hiddenLabel()
                                ->columnSpan(5),
                        ])->columns(6),

                        Group::make([
                            Placeholder::make('Formato da data')
                                ->label('Formato da data')
                                ->columnSpan(1),
                            Select::make('settings.date_format')
                                ->required()
                                ->options([])
                                ->searchable()
                                ->hiddenLabel()
                                ->columnSpan(5),
                        ])->columns(6),

                        Group::make([
                            Placeholder::make('Notificações por email')
                                ->columnSpan(1),

                            Group::make([
                                Toggle::make('settings.notification.email')
                                    ->hiddenLabel(),
                            ])->extraAttributes(['class' => 'float-right']),

                        ])->columns(2),

                        Group::make([
                            Placeholder::make('Notificações por whatsapp')
                                ->columnSpan(1),

                            Group::make([
                                Toggle::make('settings.notification.whatsapp')
                                    ->hiddenLabel(),
                            ])->extraAttributes(['class' => 'float-right']),

                        ])->columns(2),
                    ]),

            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        try {

            $this->user->update($data);

            Notification::make()
                ->title('Dados alterados com sucesso!')
                ->body('Dados alterados com sucesso!')
                ->success()
                ->send();
        } catch (Exception $e) {

            Notification::make()
                ->title('Erro ao alterar dados!')
                ->body('Erro ao alterar dados!')
                ->danger()
                ->send();
        }
    }

}
