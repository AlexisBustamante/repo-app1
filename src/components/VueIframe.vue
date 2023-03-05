<template>
    <v-container>
        <vue-iframe :src="''" allow="camera *; geolocation *; microphone *; autoplay *" frame-id="my-ifram" @load="onLoad"
            name="my-frame" width="150px" height="200px" />
    </v-container>
</template>

<script>
export default {
    name: 'MyIframe',
    data: () => ({
        myIframe: null
    }),
    methods: {
        onLoad(frame) {
            this.myIframe = frame.contentWindow
        }
    }
}
</script>