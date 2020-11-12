<template>
    <div class="mb-2">
        <button
            :disabled="disabled"
            class="btn btn-info"
            @click.prevent="ExportUserorderWork"
        >
            Export Task Work
        </button>
    </div>
</template>
<script>
    export default {
        name: "UserExport",
        data() {
            return {
                disabled: false,
            };
        },
        methods: {
            ExportUserorderWork() {
                this.disabled = true;
                axios
                    .get("/ajaxuser/ordersExportUsers", {
                        responseType: "blob",
                    })
                    .then((response) => {
                        const downloadUrl = window.URL.createObjectURL(
                            new Blob([response.data])
                        );
                        const link = document.createElement("a");
                        let d = new Date();
                        let filename =
                            d.getDate() +
                            "_" +
                            d.getMonth() +
                            "_" +
                            d.getFullYear() +
                            "_" +
                            d.getHours() +
                            "_" +
                            d.getMinutes() +
                            "_" +
                            d.getSeconds();
                        link.href = downloadUrl;
                        link.setAttribute("download", filename + "_export.txt");
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    })
                    .catch((error) => {
                        this.showShwal("error", "Error");
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            },
        },
    };
</script>


