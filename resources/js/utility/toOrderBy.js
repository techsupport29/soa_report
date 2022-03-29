import moment from 'moment'
import {orderBy} from 'lodash'

const toOrderBy = (array, order = 'desc') => {
  
    const ordered = orderBy(array, ["date_of_soa"], [order]);
    return ordered.map((o) => ({
        ...o,
        date_of_soa: moment(o.date_of_soa).format("DD MMM YYYY"),
    }));
}

export default toOrderBy