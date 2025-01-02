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
                class="bg-gradient-to-b text-white w-full from-primary to-secondary h-full"
            >
                <div class="relative p-4 h-full">
                    <div
                        class="absolute inset-0 bg-[url('/arabic-pattern.svg')] bg-cover bg-opacity-50 z-0 opacity-25"
                    ></div>
                    <div
                        class="relative z-10 flex justify-center items-center flex-col space-y-6 h-full"
                    >
                        <h1
                            class="text-3xl font-bold text-center w-full text-darker"
                        >
                            Log in and join activities!
                        </h1>
                        <div
                            class="md:w-1/6 sm:w-1/2 w-full flex flex-col space-y-6"
                        >
                            <div class="flex flex-col w-full">
                                <label for="">
                                    Email address or username
                                </label>
                                <input type="text" />
                            </div>
                            <div class="flex flex-col">
                                <div class="flex justify-between">
                                    <label for=""> Password </label>
                                    <span class="flex items-center">
                                        <span class="mdi mdi-eye"></span>
                                        Show</span
                                    >
                                </div>
                                <input type="password" />
                            </div>
                            <small
                                ><a class="underline" href="#"
                                    >Forgot your password?</a
                                ></small
                            >
                            <button class="btn-secondary flex justify-between">
                                Sign In
                                <span class="mdi mdi-login"></span>
                            </button>
                            <p class="text-darker text-center">
                                Don't have an account?
                                <Link
                                    :href="route('register')"
                                    as="button"
                                    class="text-white"
                                >
                                    Sign Up</Link
                                >
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
