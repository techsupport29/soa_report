const defineEmail = (arrayEmail) => {
    if (arrayEmail != null) {
        const emailMap = arrayEmail.map((ed) => ed["email"]); // Map array email and return only the email string
        // const ee = emailMap.reduce((prev, current) => { // 
        //     return current + " " + prev;
        // }, "");
        const ee = emailMap.toString()
        return ee.trim();
    }
};

const defineContact = (arrayContact) => {
    if (arrayContact != null) {
        const contact = arrayContact.map((ed) => ed["contact_number"]);
        const cc = contact.toString()
        return cc.trim();
    }
};

const sanitizeBank = (array) => {
  
    if (array != null) {
        const bankAccN = array.map(item => item['account_name'])
        const bankN = array.map(item => item['bank_name'])
        const bankNo = array.map(item => item['bank_number'])

        const bankAccountName = bankAccN.join(" | ")
        const bankName = bankN.join(" | ")
        const bankNumber = bankNo.join(" | ")

        return {
            bankAccountName,
            bankName,
            bankNumber,
        }
    }
}

export { defineEmail, defineContact, sanitizeBank };
