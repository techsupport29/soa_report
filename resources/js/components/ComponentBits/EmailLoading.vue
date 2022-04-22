<template lang="">
    <div class="plane-container">
     
        <div>
            <img src="/image/manok.gif">
            <h3 class="text-white">Sending emails. Please wait...</h3>
        </div>
               
       
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
