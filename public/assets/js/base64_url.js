/**
 * use this to make a Base64 encoded string URL friendly,
 * i.e. '+' and '/' are replaced with '-' and '_' also any trailing '='
 * characters are removed
 *
 * @param {String} str the encoded string
 * @returns {String} the URL friendly encoded String
 */
function encode_id(str){
    str         = btoa(str);
    replace     = str.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    return replace
}

/**
 * Use this to recreate a Base64 encoded string that was made URL friendly
 * using Base64EncodeurlFriendly.
 * '-' and '_' are replaced with '+' and '/' and also it is padded with '+'
 *
 * @param {String} str the encoded string
 * @returns {String} the URL friendly encoded String
 */
function decode_id(str){
    str     = (str + '===').slice(0, str.length + (str.length % 4));
    replace = str.replace(/-/g, '+').replace(/_/g, '/');
    return atob(str);
}
