import {toOrderBy} from '../utility'
import moment from 'moment'

const withStatus = async (site,page,perPage, status, dates) => {
    const endDate = (Array.isArray(dates) && dates.length > 1) && moment(dates[1], "YYYY-MM-DD")
    .add(1, "days")
    .format("YYYY-MM-DD");

    const fromTo = (Array.isArray(dates) && dates.length > 1) && `dateFrom=${dates[0]}&dateTo=${endDate}`
    const newArray = [];
    const perP = perPage ? perPage : 10
    // const {data} = await axios.get(`api/importwithstatus?page=${page}&per_page=${perPage}`);
    const {data} = await axios.get(`api/importwithstatus?${fromTo}&site=${site}&status=done&page=${page}&per_page=${perPage}`);
    // const data = await axios.get(`api/importwithstatus`);

    data.data.forEach((dObj) => {
        const arenaName =
            dObj.arena_name.indexOf("~") > -1
                ? dObj.arena_name.replace(/\~/g, "/")
                : dObj.arena_name;

        const obj = {
            ...dObj,
            arena_name: arenaName,
        };
        newArray.push(obj);
    });

    return {
        withStatusData: toOrderBy(newArray),
        page: data.current_page,
        total: data.total,
        numberOfPages: data.last_page
    };
};

const soa = async (site, page, perPage, status = null, dates) => {
    const endDate = (Array.isArray(dates) && dates.length > 1) && moment(dates[1], "YYYY-MM-DD")
    .add(1, "days")
    .format("YYYY-MM-DD");

    const fromTo = (Array.isArray(dates) && dates.length > 1) && `dateFrom=${dates[0]}&dateTo=${endDate}`
    
    const {data} = await axios.get(`api/import?${fromTo}&site=${site}&status=${status}&page=${page}&per_page=${perPage}`);



    const newArray = [];
    data.data.forEach((dObj) => {
        const arenaName =
            dObj.arena_name.indexOf("~") > -1
                ? dObj.arena_name.replace(/\~/g, "/")
                : dObj.arena_name;

        const obj = {
            ...dObj,
            arena_name: arenaName,
        };
        newArray.push(obj);
    });

    return {
        soaLists: toOrderBy(newArray),
        page: data.current_page,
        total: data.total,
        numberOfPages: data.last_page
    };
};




export { withStatus, soa, };
