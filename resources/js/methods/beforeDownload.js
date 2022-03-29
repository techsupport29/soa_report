const beforeDownload = async ({ html2pdf, options, pdfContent }) => {
    const opts = {
        ...options,
        html2canvas: {
            scale: 1,
            useCORS: true,
        },
    };

    pdfContent.style.transform = "scale(0.7)";
    pdfContent.style.height = "1000px";

    await html2pdf()
        .set(opts)
        .from(pdfContent)
        .toPdf()
        .get("pdf")
        .then((pdf) => {
            const totalPages = pdf.internal.getNumberOfPages();
            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                pdf.setFontSize(9);
                pdf.setTextColor(150);
                pdf.text(
                    "Page " + i + " of " + totalPages,
                    pdf.internal.pageSize.getWidth() * 0.88,
                    pdf.internal.pageSize.getHeight() - 0.3
                );
            }
        })
        .save()
        .then(() => {
            pdfContent.style.transform = "scale(1)";
            pdfContent.style.height = "auto";
        });
};

export { beforeDownload };
