<template lang="">
               <!-- PLEASE STAND BY -->

            <v-dialog v-model="loading" persistent width="400">
                <v-card
                    :color="progressvalue === 100 ? '#6BB3EF' : '#002050'"
                    dark
                >
                    <v-card-text>
                        {{
                           downloadingReport && progressText 
                                ? `${progressText} ${progressvalue}`
                                : downloadingReport
                                ? "Downloading..."
                                : "Please stand by"
                        }}
                        <v-progress-linear
                            v-model="progressvalue"
                            :color="
                                progressvalue === 100 ? '#6BB3EF' : '#6BB3EF'
                            "
                            class="black--text"
                            :height="downloadingReport ? '14' : '9'"
                            :stream="downloadingReport"
                            :buffer-value="progressvalue"
                            :indeterminate="!downloadingReport"
                        >
                            <template
                                v-if="downloadingReport"
                                v-slot:default="{ value }"
                            >
                                <strong
                                    :class="[
                                        progressvalue > 50
                                            ? 'black--text'
                                            : 'white--text',
                                    ]"
                                    class="text-xs"
                                    >{{
                                        value === 100
                                            ? "Complete"
                                            : `${Math.ceil(value)}%`
                                    }}</strong
                                >
                            </template>
                        </v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <!-- PLEASE STAND BY -->
</template>
<script>
export default {
    name: 'loading-progress',
    props: {
        loading: Boolean,
        downloadingReport: Boolean,
        progressvalue: Number,
        progressText: String
    }
}
</script>
<style lang="">
    
</style>