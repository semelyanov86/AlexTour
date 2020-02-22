
export default function parseObjectRelatedResult(records) {
    var resulting = [];
    var indexPlus = 0;
    for (var prop in records) {
        if (indexPlus < 3) {
            if (records[prop] && typeof records[prop] == 'object') {
                // Vue.set(records[prop], 'main', true);
                records[prop].main = true;
            }
        } else {
            if (records[prop]  && typeof records[prop] == 'object') {
                // Vue.set(records[prop], 'main', false);
                records[prop].main = false;
            }
        }
        if (typeof records[prop] == 'object') {
            resulting.push(records[prop]);
        }
        indexPlus++;
    }
    return resulting;
}