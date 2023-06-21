
const isNumeric = (value) => {
    return !isNaN(parseFloat(value)) && isFinite(value);
}
const isInteger = (value) => {
    return !isNaN(parseFloat(value)) && isFinite(value) && Number.isInteger(value);
}
const isFloat = (value) => {
    return !isNaN(parseFloat(value)) && isFinite(value);
}
const isString = (value) => {
    return typeof value === 'string';
}
const isBoolean = (value) => {
    return typeof value === 'boolean';
}
const isObject = (value) => {
    return typeof value === 'object';
}
const isArray = (value) => {
    return Array.isArray(value);
}
const isFunction = (value) => {
    return typeof value === 'function';
}
export const isNull = (value) => {
    return value === null;
}
const isUndefined = (value) => {
    return typeof value === 'undefined';
}
const isDate = (value) => {
    return value instanceof Date;
}
const isRegExp = (value) => {
    return value instanceof RegExp;
}
const isSymbol = (value) => {
    return typeof value === 'symbol';
}
const isPromise = (value) => {
    return value instanceof Promise;
}
const isSet = (value) => {
    return value instanceof Set;
}
const isMap = (value) => {
    return value instanceof Map;
}
const isWeakSet = (value) => {
    return value instanceof WeakSet;
}
const isWeakMap = (value) => {

    return value instanceof WeakMap;
}
const isNullish = (value) => {
    return value === null || value === undefined;
}
const isTruthy = (value) => {
    return value !== null && value !== undefined;
}
const isFalsy = (value) => {
    return value === null || value === undefined;
}
const isPrimitive = (value) => {
    return isString(value) || isNumber(value) || isBoolean(value);
}
const isIterable = (value) => {
    return isObject(value) && isFunction(value[Symbol.iterator]);
}
const isAsyncFunction = (value) => {
    return isFunction(value) && isAsync(value);
}
const isEmpty = (value) => {
    return value === null || value === undefined || value.length === 0;
}

const isNotEmpty = (value) => {
    return !isEmpty(value);
}
const arrayMap = (arr, fn) => {
    return arr.map(fn);
}
const arrayFilter = (arr, fn) => {
    return arr.filter(fn);
}
const arrayReduce = (arr, fn, init) => {
    return arr.reduce(fn, init);
}
const arrayEvery = (arr, fn) => {
    return arr.every(fn);
}
const arraySome = (arr, fn) => {

    return arr.some(fn);
}
const arrayIncludes = (arr, value) => {
    return arr.includes(value);
}
const arrayIndexOf = (arr, value) => {
    return arr.indexOf(value);
}
const arrayFind = (arr, fn) => {
    return arr.find(fn);
}
const arrayKeys = (obj) => {
    return Object.keys(obj);
}

const arrayValues = (obj) => {
    return Object.values(obj);
}
const arrayEntries = (obj) => {
    return Object.entries(obj);
}

const arrayPush = (arr, value) => {
    return arr.push(value);
}
const arrayPop = (arr) => {
    return arr.pop();
}

const arrayRemove = (arr, value) => {
    return arr.splice(arr.indexOf(value), 1);
}
const arrayRemoveByIndex = (arr, index) => {
    return arr.splice(index, 1);
}

const arrayRemoveByCondition = (arr, fn) => {
    return arr.filter(fn);
}
const arraySort = (arr, fn) => {
    return arr.sort(fn);
}
const arrayReverse = (arr) => {
    return arr.reverse();
}
const arrayJoin = (arr, separator) => {
    return arr.join(separator);
}

const arrayFlat = (arr) => {
    return arr.flat();
}
const arrayExplode = (arr, separator) => {
    return arr.split(separator);
}

const arrayImplode = (arr, separator) => {
    return arr.join(separator);
}

const arrayUnique = (arr) => {
    return [...new Set(arr)];
}
const arrayUniqueBy = (arr, fn) => {
    return [...new Set(arr.map(fn))];
}

const arrayGroupBy = (arr, fn) => {
    return arr.reduce((acc, item) => {
        const key = fn(item);
        if (!acc[key]) {
            acc[key] = [];
        }
        acc[key].push(item);
        return acc;
    }, {});
}
const arrayCountBy = (arr, fn) => {
    return arr.reduce((acc, item) => {
        const key = fn(item);
        acc[key] = (acc[key] || 0) + 1;
        return acc;
    }, {});
}
const arrayCountOccurrences = (arr, value) => {
    return arr.reduce((acc, item) => {
        if (item === value) {
            acc += 1;
        }
        return acc;
    }, 0);
}

const arrayReturnObjects = (arr, value) => {
    arr.forEach(item => {
        if (item === value) {
            return item;
        }
        if (Array.isArray(item)) {
            arrayReturnObjects(item, value);
        }
    })
}
const arrayReturnValues = (arr, value) => {

    for (let i = 0; i < arr.length; i++) {
        if (arr[i] === value) {
            return arr;
        }
        if (Array.isArray(arr[i])) {
            returnValueFromArrayIterative(arr[i], value);
        }
    }
}

export default {
    isNumeric,
    isInteger,
    isFloat,
    isString,
    isBoolean,
    isObject,
    isArray,
    isFunction,
    isNull,
    isUndefined,
    isDate,
    isRegExp,
    isSymbol,
    isPromise,
    isSet,
    isMap,
    isWeakSet,
    isWeakMap,
    isNullish,
    isTruthy,
}

// export default() => {

//     return {
//     isNumeric,
//     isInteger,
//     isFloat,
//     isString,
//     isBoolean,
//     isObject,
//     isArray,
//     isFunction,
//     isNull,
//     isUndefined,
//     isDate,
//     isRegExp,
//     isSymbol,
//     isPromise,
//     isSet,
//     isMap,
//     isWeakSet,
//     isWeakMap,
//     isNullish,
//    isTruthy,
//    isFalsy,
//    isPrimitive,
//    isIterable,
//    isAsyncFunction,
//    isEmpty,
//    isNotEmpty,
//    arrayMap,
//    arrayFilter,
//    arrayReduce,
//    arrayEvery,
//    arraySome,
//    arrayIncludes,
//    arrayIndexOf,
//    arrayFind,
//    arrayKeys,
//    arrayValues,
//    arrayEntries,
//    arrayPush,
//    arrayPop,
//    arrayRemove,
//    arrayRemoveByIndex,
//    arrayRemoveByCondition,
//    arraySort,
//    arrayReverse,
//    arrayJoin,
//    arrayFlat,
//    arrayExplode,
//    arrayImplode,
//    arrayUnique,
//    arrayUniqueBy,
//    arrayGroupBy,
//    arrayCountBy,
//    arrayCountOccurrences,
//    arrayReturnObjects,
//    returnObjectIterative
//     }
// }
