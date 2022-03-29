import numbro from 'numbro';


const numberFormat = (number = 0, decimal = 2) => {
    return numbro(number).format({
        thousandSeparated: true,
        negative: "parenthesis",
        mantissa: decimal
    })
}

const numberUnformat = (string) => {
    return numbro.unformat(string)
}

const toFixed = (number, decimal = 3) => {
	return number.toFixed(decimal);
};

const formatter = (number) => {
	return new Intl.NumberFormat('en-PH', {
		minimumFractionDigits: 2, 
		maximumFractionDigits: 2, 
	  }).format(number);
 }

const moneyFormat = (num, isNotRounded = false, decimal = 2,) => {

    // const money = parseFloat(num) < -1 ? parseFloat(num) : toFixed(parseFloat(num))
    const money = isNotRounded ? parseFloat(num) : toFixed(parseFloat(num))   // round up to thousandth

    // const money = parseFloat(num)
    return numberFormat(formatter(money), decimal) // round up number of decimal place

};


export { numberFormat, numberUnformat, moneyFormat }