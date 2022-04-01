<template lang="">
    <div class="plane-container">
        <div id="view" class="plane">
            <div id="camera" class="plane">
                <div id="floor" class="plane"></div>
                <div id="airplane" class="plane">
                    <div class="wingLeft plane"></div>
                    <div class="wingRight plane"></div>
                    <div class="bodyLeft plane"></div>
                    <div class="bodyRight plane"></div>
                    <div class="bodyShadow plane"></div>
                    <div class="cloud plane"></div>
                    <div class="cloudShadow plane"></div>
                </div>
            </div>
        </div>
        <h3 class="text-white">Sending emails. Please wait...</h3>
    </div>
</template>
<script>
export default {
    name: "email-loading",
    data: () => ({
        overlay: true,
        
    }),

};
</script>
<style lang="" scoped></style>
