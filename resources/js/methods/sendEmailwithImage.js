import html2canvas from "html2canvas";

const sendEmailwithImage = async (details, dateEvent, codeEvent, el,type,cc) => {
    const options = {
        type: "dataURL",
        backgroundColor: "transparent",
    };
    const printCanvas = await html2canvas(el, options);

    const link = document.createElement("a");

    link.download = `${details.arena}.png`;
    link.href = printCanvas.toDataURL("image/png");

    axios.post('api/sendEmail',{
        cc: cc,
        data : details,
        group : type,
        link : link.href ,
        date : dateEvent
        }).then(({data}) => {
            console.log(data);
        });

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




export { sendEmailwithImage };
