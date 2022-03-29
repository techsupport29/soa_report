const reportGenerate = (codeEvent, html2Pdf) => {
    html2Pdf.downloadPdf();
    axios
        .put("api/arenaStatus", [{ codeEvent, status: "done" }])
        .then(
            () => (
                Fire.$emit("AfterCreate"),
                swal.fire("convert to pdf!", "successfully", "success")
            )
        );

    return {
        dialog: false,
    };
};

export { reportGenerate };
