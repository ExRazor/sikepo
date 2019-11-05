/**
 * use this to make a Base64 encoded string URL friendly,
 * i.e. '+' and '/' are replaced with '-' and '_' also any trailing '='
 * characters are removed
 *
 * @param {String} str the encoded string
 * @returns {String} the URL friendly encoded String
 */
function encode_id(str){
    var key  = 'KfqUsXXhY0nhhqrmovEx5qQZ';
    // let encryption = new Encryption();
    // var encrypted = encryption.encrypt(str, key);

    str         = btoa(key+str);
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
    var key  = 'KfqUsXXhY0nhhqrmovEx5qQZ';
    // let encryption = new Encryption();
    // var decrypted = encryption.decrypt(encrypted, key);

    // return decrypted;

    str     = (str + '===').slice(0, str.length + (str.length % 4));
    replace = str.replace(/-/g, '+').replace(/_/g, '/');
    decrypt = atob(str);

    return decrypt.replace(key, '');
}
