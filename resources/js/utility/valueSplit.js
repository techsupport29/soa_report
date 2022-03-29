const valueSplit = (data) => {
            let target = {};
            if(data != null && typeof data == "string" && data.indexOf(': ') > -1) {
              
                let splitpair =  data.split(': ');
                let key = splitpair[0].charAt(0).toLowerCase() + splitpair[0].slice(1).split(' ').join('');
                target[key] = splitpair[1];
            
                return target
            }
}

export default valueSplit