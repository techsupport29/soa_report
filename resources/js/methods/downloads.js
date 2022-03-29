import html2canvas from "html2canvas";

const imageDownload = async (details, codeEvent, el) => {
    const options = {
        type: "dataURL",
        backgroundColor: "transparent",
    };
    const printCanvas = await html2canvas(el, options);

    const link = document.createElement("a");

    link.download = `${details.arena}.png`;
    link.href = printCanvas.toDataURL("image/png");
    document.body.appendChild(link);
    link.click();

    setTimeout(() => {
        document.body.removeChild(link); // On modern browsers you can use `tempLink.remove();`
    }, 100);

    const arenaStatus = await axios.put("api/arenaStatus", [
        { codeEvent, status: "done" },
    ]);

    return arenaStatus;
};

export { imageDownload };
