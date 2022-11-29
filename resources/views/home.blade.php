@extends('layout')

@section('content')
    <div class="min-h-full">
        <div class="py-10">
            <header>
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">lkkr padelle</h1>
                </div>
            </header>
            <main id="app" x-data="app()" x-on:changeDate="console.log('test')">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="px-4 py-8 space-y-4 sm:px-0">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                            <select x-model="selectedUser"
                                    id="name"
                                    name="name"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                <option selected hidden>Selecteer een naam</option>
                                <template x-for="user in users">
                                    <option :value="user.id" x-text="user.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Locatie</label>
                            <select x-on:change="updateTimeslots()"
                                    x-model="selectedLocation"
                                    id="location"
                                    name="location"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                <option selected hidden>Selecteer een locatie</option>
                                <template x-for="location in locations">
                                    <option :value="location.name.toLowerCase()" x-text="location.name"></option>
                                </template>
                            </select>
                        </div>
                            <div id="date" x-on:update.window="updateTimeslots()"></div>
                        <div>
                            <div class="grid overflow-hidden grid-cols-4 grid-rows-8 gap-2">
                                <template x-for="(data, timeslot) in timeslots">
                                    <div
                                        :class="{'bg-emerald-500 cursor-pointer': data.available,
                                            'bg-emerald-300 line-through': !data.available,
                                            'border-4 border-yellow-500': data.users.length >= 4}"
                                        x-on:click="(data.available) ? updateAvailability(timeslot) : null"
                                        class="py-1 text-white text-center rounded">
                                        <span x-text="timeslot"></span>
                                        <ul>
                                            <template x-for="user in data.users">
                                                <li x-text="user"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-8 space-y-4 sm:px-0">
                        <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Beschikbare data</h1>
                        <template x-for="(dates, locationName) in matches">
                            <h2 class="text-2xl font-bold leading-tight tracking-tight text-gray-900" x-text="locationName"></h2>

                                <template x-for="i in 2">
                                    <h3 class="text-1xl font-bold leading-tight tracking-tight text-gray-900" x-text="i"></h3>
                                </template>

                        </template>


{{--                        <div>--}}
{{--                            <div class="grid overflow-hidden grid-cols-4 grid-rows-8 gap-2">--}}

{{--                                    <div class="bg-emerald-500 py-1 text-white text-center rounded">--}}
{{--                                        <span>asd</span>--}}
{{--                                        <ul>--}}
{{--                                            <template x-for="user in data.users">--}}
{{--                                                <li x-text="user"></li>--}}
{{--                                            </template>--}}
{{--                                        </ul>--}}
{{--                                </template>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
