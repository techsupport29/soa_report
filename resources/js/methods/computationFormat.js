const computationSoa = (data) => {
   
    const wht = data.wht;
    const totalMWBets = data.total_meron_wala;
    const drawCancelled = data.draw_cancelled;
    const totalPayoutPaid = data.total_payout_paid;
    const cdPaid = data.draw_cancelled_paid;
    const drawPaid = data.draw_paid;
    const cUnpaid = data.cancelled_unpaid;
    const salesDeduction = data.salesDeductionTablet;
    const otherCommissionIntel05 = data.otherCommissionIntel05;
    const totalMWMobile = data.total_win_mobile;
    const drawMobile = data.draw_mobile;
    const depositReplenish = parseFloat(data.for_total).toFixed(2);
    // const totalCommission = data.totalCommission;
    const netOpCommission = data.netOperatorsCommission;
    const consolidatorsCommission = data.consolidatorsCommission;
    const paymentForOutstandingBalance = data.paymentForOutstandingBalance;
    const otherDeductiblesFromCommission = data.otherDeductiblesFromCommission;
    const safetyFund = data.safetyFund;
    const safetyFundMob = data.safetyFundMob;
    const otherCommIntMob = data.otherCommIntMob;
    const consolCommMob = data.consolCommMob;
    const payOutsBalMob = data.payOutsBalMob;
    const totalSafetyFund = parseFloat(safetyFund) + parseFloat(safetyFundMob);
    const totalOtherCommIntel =
        parseFloat(otherCommissionIntel05) + parseFloat(otherCommIntMob);
    const totalConsolComm =
        parseFloat(consolidatorsCommission) + parseFloat(consolCommMob);
    const totalPayOutBal =
        parseFloat(paymentForOutstandingBalance) + parseFloat(payOutsBalMob);
    const totalCommissionDeductables = 
        parseFloat(totalOtherCommIntel) + parseFloat(netOpCommission) -  
        parseFloat(totalConsolComm) - parseFloat(totalSafetyFund) - 
        parseFloat(totalPayOutBal) - parseFloat(otherDeductiblesFromCommission);

    const otherTotalCommission = parseFloat(data.mwTwo) + parseFloat(data.mwTwoMobile) + parseFloat(data.drawTwo) + parseFloat(data.drawTwoMobile);
    

    // const lessWithHoldingTax = parseFloat(otherTotalCommission * 0.2 * 0.02);



    const netCommWithTax = parseFloat(totalCommissionDeductables) - parseFloat(data.wht);
    const whTax = parseFloat(data.wht).toFixed(2);
    const totalDepositReplenish = parseFloat(data.netWinLoss) - parseFloat(netCommWithTax);
    // const totalReplenish = parseFloat(depositReplenish) + parseFloat(lessWithHoldingTax);
    // const depositReplenishWithTax = parseFloat(depositReplenish)


    console.log('data', data);
    const computation = {
        totalMWBets,
        drawCancelled,
        totalPayoutPaid,
        cdPaid,
        drawPaid,
        cUnpaid,
        salesDeduction,
        netOpCommission,
        totalCommissionDeductables,
        depositReplenish,
        drawMobile,
        totalMWMobile,
        wht,
        whTax : whTax,
        otherTotalCommission : otherTotalCommission,
        otherCommissionIntel0 : totalOtherCommIntel,
        safetyFund: totalSafetyFund,
        totalSafetyFund : totalSafetyFund,
        consolidatorsCommission: totalConsolComm,
        totalConsolComm : totalConsolComm,
        paymentForOutstandingBalance: totalPayOutBal,
        otherDeductiblesFromCommission : otherDeductiblesFromCommission,
        // lessWithHoldingTax : lessWithHoldingTax,
        netCommWithTax : netCommWithTax.toFixed(2),
        totalDepositReplenish : totalDepositReplenish,
        // totalReplenish: totalReplenish,
        ...data,
    };

    return computation;
};

export { computationSoa };
