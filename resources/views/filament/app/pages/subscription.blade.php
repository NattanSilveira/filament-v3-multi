<x-filament-panels::page>
    @if($this->hasSubscription)
        <div class="px-4 py-10 sm:px-6 lg:flex-auto lg:px-2 lg:py-10" wire:loading.class="opacity-50">
            <div class="mx-auto max-w-3xl space-y-8 lg:mx-0 lg:max-w-none">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Assinatura</h3>
                        <p class="mt-1 max-w
                        -2xl text-sm text-gray-500">Atualize os dados da sua assinatura.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Plano</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->plan }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->status }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Data de início</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->startDate }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Data de término</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->endDate }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    @else

{{--    voce nao possui uma assinatura ativa--}}
        <form wire:submit="submit">
            <div class="px-4 py-10 sm:px-6 lg:flex-auto lg:px-2 lg:py-10" wire:loading.class="opacity-50">
                <div class="mx-auto space-y-8 lg:mx-0 lg:max-w-none">
                    <div class="">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Você não possui uma assinatura ativa</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Escolha um plano para começar a usar o sistema.</p>
                        </div>

                        <div class="px-4 py-10 sm:px-6 lg:flex-auto lg:px-2 lg:py-10">
                            <div class="mx-auto max-w-3xl space-y-8 lg:mx-0 lg:max-w-none">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="border rounded-lg shadow-sm p-6 text-center">
                                        <h4 class="text-lg font-semibold">Hobby</h4>
                                        <p class="text-xl font-bold">$15 <span class="text-base font-normal">/month</span></p>
                                        <ul class="mt-4 text-left space-y-2">
                                            <li>5 products</li>
                                            <li>Up to 1,000 subscribers</li>
                                            <li>Basic analytics</li>
                                        </ul>
                                        <x-filament::button class="mt-6">Buy plan</x-filament::button>
                                    </div>

                                    <div class="border rounded-lg shadow-sm p-6 text-center">
                                        <h4 class="text-lg font-semibold">Freelancer</h4>
                                        <p class="text-xl font-bold">$30 <span class="text-base font-normal">/month</span></p>
                                        <ul class="mt-4 text-left space-y-2">
                                            <li>5 products</li>
                                            <li>Up to 1,000 subscribers</li>
                                            <li>Basic analytics</li>
                                            <li>48-hour support response time</li>
                                        </ul>
                                        <x-filament::button class="mt-6">Buy plan</x-filament::button>
                                    </div>

                                    <div class="border rounded-lg shadow-sm p-6 text-center bg-blue-50">
                                        <h4 class="text-lg font-semibold">Startup</h4>
                                        <p class="text-xl font-bold">$60 <span class="text-base font-normal">/month</span></p>
                                        <ul class="mt-4 text-left space-y-2">
                                            <li>25 products</li>
                                            <li>Up to 10,000 subscribers</li>
                                            <li>Advanced analytics</li>
                                            <li>24-hour support response time</li>
                                            <li>Marketing automations</li>
                                        </ul>
                                        <x-filament::button class="mt-6 bg-blue-500 text-white">Buy plan</x-filament::button>
                                    </div>

                                    <div class="border rounded-lg shadow-sm p-6 text-center">
                                        <h4 class="text-lg font-semibold">Enterprise</h4>
                                        <p class="text-xl font-bold">$90 <span class="text-base font-normal">/month</span></p>
                                        <ul class="mt-4 text-left space-y-2">
                                            <li>Unlimited products</li>
                                            <li>Unlimited subscribers</li>
                                            <li>Advanced analytics</li>
                                            <li>1-hour, dedicated support response time</li>
                                            <li>Marketing automations</li>
                                            <li>Custom reporting tools</li>
                                        </ul>
                                        <x-filament::button class="mt-6">Buy plan</x-filament::button>
                                    </div>
                                </div>


                                <div x-data="{ openTab: 1 }" class="p-8">
                                    <div class="max-w-md mx-auto">
                                        <div class="mb-4 flex space-x-4 p-2 bg-white rounded-lg shadow-md">
                                            <button x-on:click="openTab = 1" :class="{ 'bg-blue-600 text-white': openTab === 1 }" class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">Section 1</button>
                                            <button x-on:click="openTab = 2" :class="{ 'bg-blue-600 text-white': openTab === 2 }" class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">Section 2</button>
                                            <button x-on:click="openTab = 3" :class="{ 'bg-blue-600 text-white': openTab === 3 }" class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">Section 3</button>
                                        </div>

                                        <div x-show="openTab === 1" class="transition-all duration-300 bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600">
                                            <h2 class="text-2xl font-semibold mb-2 text-blue-600">Section 1 Content</h2>
                                            <p class="text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam justo nec justo lacinia, vel ullamcorper nibh tincidunt.</p>
                                        </div>

                                        <div x-show="openTab === 2" class="transition-all duration-300 bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600">
                                            <h2 class="text-2xl font-semibold mb-2 text-blue-600">Section 2 Content</h2>
                                            <p class="text-gray-700">Proin non velit ac purus malesuada venenatis sit amet eget lacus. Morbi quis purus id ipsum ultrices aliquet Morbi quis.</p>
                                        </div>

                                        <div x-show="openTab === 3" class="transition-all duration-300 bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600">
                                            <h2 class="text-2xl font-semibold mb-2 text-blue-600">Section 3 Content</h2>
                                            <p class="text-gray-700">Fusce hendrerit urna vel tortor luctus, nec tristique odio tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
                                        </div>
                                    </div>
                                </div>

                                <x-filament::button type="submit" form="submit" class="mt-8">
                                    Atualizar dados
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    @endif
</x-filament-panels::page>
