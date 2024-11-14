<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const loading = ref(true);
const qrcode = ref(null);

onMounted(() => {
    axios.get(route('profile.qrcode.index')).then((response) => {
        qrcode.value = response.data.data;
        loading.value = false;
    });
});

function downloadQrCode() {
    svg2Image(qrcode.value, 300, 300, 'png', function (dataUrl) {
        var link = document.createElement("a");
        link.href = dataUrl;
        link.download = "qrcode.png";
        link.click();
    });
}

function svg2Image(svg, width, height, format, callback) {
    format = format ? format : 'png';
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    canvas.width = width;
    canvas.height = height;
    var image = new Image();
    image.onload = function () {
        context.clearRect(0, 0, width, height);
        context.drawImage(image, 0, 0, width, height);
        var pngData = canvas.toDataURL('image/' + format);
        callback(pngData);
    };
    image.src = svg;
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Your QR Code
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Download your QR code to join the activity easily.
            </p>
        </header>

        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <v-img
                    :src="qrcode"
                    class="mx-auto h-48 w-48 rounded-full"
                >
                    <template v-slot:placeholder>
                        <div class="d-flex align-center justify-center fill-height">
                            <v-progress-circular
                                color="grey-lighten-4"
                                indeterminate
                            ></v-progress-circular>
                        </div>
                    </template>
                </v-img>
                <v-btn
                    :loading="loading"
                    @click="downloadQrCode()"
                >
                    Download
                </v-btn>
            </div>
        </div>
    </section>
</template>
