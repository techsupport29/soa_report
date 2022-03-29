const printSoa = (divName) => {
    const divContent = document.getElementById(divName);
    const printContents = divContent.innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
};

export { printSoa };
