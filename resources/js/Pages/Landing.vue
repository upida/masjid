<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const cards = [
    {
        title: "Manajemen Aktivitas",
        desc: "Kelola jadwal sholat, pengajian, dan acara lainnya dengan praktis dan terorganisir.",
    },

    {
        title: "Manajemen Aktivitas",
        desc: "Kelola jadwal sholat, pengajian, dan acara lainnya dengan praktis dan terorganisir.",
    },
    {
        title: "Manajemen Aktivitas",
        desc: "Kelola jadwal sholat, pengajian, dan acara lainnya dengan praktis dan terorganisir.",
    },
    {
        title: "Manajemen Aktivitas",
        desc: "Kelola jadwal sholat, pengajian, dan acara lainnya dengan praktis dan terorganisir.",
    },
];
</script>

<template>
    <div class="min-h-screen relative flex flex-col">
        <nav
            class="sticky top-0 z-50 bg-white flex justify-between items-center px-4 py-2"
        >
            <img src="/assets/logo.svg" alt="" />
            <ul class="gap-8 md:flex hidden">
                <li>
                    <a href="#" class="flex gap-2">
                        <span class="mdi mdi-menu-down"></span>Activities</a
                    >
                </li>
                <li><a href="#">Activities</a></li>
                <li><a href="#">Activities</a></li>
                <li><a href="#">Activities</a></li>
            </ul>
            <Link
                :href="route('login')"
                as="button"
                class="btn-primary md:flex hidden"
            >
                <span class="mdi mdi-login"></span> Login</Link
            >
            <div class="sm:hidden flex items-center gap-3">
                <Link :href="route('login')" as="button" class="btn-primary">
                    <span class="mdi mdi-login"></span>
                    Login</Link
                >

                <Link :href="route('login')" as="button">
                    <span class="mdi mdi-menu"></span>
                </Link>
            </div>
        </nav>
        <div class="absolute top-0 left-0 z-10 w-full h-full">
            <section
                class="bg-gradient-to-b text-white w-full from-primary to-secondary h-3/4"
            >
                <div class="relative p-4 h-full">
                    <div
                        class="absolute inset-0 bg-[url('/assets/arabic-pattern.svg')] bg-cover bg-opacity-50 z-0 opacity-25"
                    ></div>
                    <div
                        class="relative z-10 flex justify-center text-center items-center flex-col space-y-6 h-full"
                    >
                        <!-- Konten di sini -->
                        <h1 class="text-3xl font-bold">
                            Kelola Aktivitas Masjid, Praktis dan Modern
                        </h1>
                        <p>
                            Atur jadwal pengajian, absensi jamaah, dan aktivitas
                            masjid dalam satu aplikasi
                        </p>
                        <button class="btn-secondary">
                            Coba Sekarang
                            <span class="mdi mdi-arrow-right"></span>
                        </button>
                    </div>
                </div>
            </section>
            <section
                class="flex flex-col text-black bg-white h-auto md:text-start text-center items-center py-12 gap-y-6 w-full"
            >
                <h1 class="text-3xl font-bold text-primary">
                    Meningkatkan Pengelolaan Masjid dengan Teknologi Cerdas
                </h1>
                <p class="">
                    Solusi modern untuk kemudahan pengelolaan aktivitas masjid
                    dan jamaah.
                </p>
                <div
                    class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-6 items-center px-16 mt-10 flex-wrap"
                >
                    <div
                        v-for="(item, index) in cards"
                        :key="index"
                        class="w-fit flex flex-col space-y-3 md:items-start items-center"
                    >
                        <div
                            class="bg-tertiary p-2 w-10 h-10 rounded-full items-center flex justify-center"
                        >
                            <span
                                class="mdi mdi-clock-time-five-outline"
                            ></span>
                        </div>

                        <p
                            class="text-primary font-semibold md:text-xl text-sm"
                        >
                            {{ item.title }}
                        </p>

                        <p class="text-center md:text-left">{{ item.desc }}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
