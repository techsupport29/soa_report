// Merge Object
const mergeObject = (array) => {
    return array.reduce(function(result, current) {
        return Object.assign(result, current);
    }, {})
}

export default mergeObject
