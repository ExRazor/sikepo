var key = 'KfqUsXXhY0nhhqrmovEx5qQZ/ZW9kDyAywthWRCa0mQ=';

    var nilai = $('input[name=_id]').val();
    console.log(nilai);

    var encrypted_json = JSON.parse(Base64.decode(nilai));

    // Now I try to decrypt it.
    var decrypted = CryptoJS.AES.decrypt(encrypted_json.value, CryptoJS.enc.Base64.parse(key), {
        iv : CryptoJS.enc.Base64.parse(encrypted_json.iv)
    });
    var encrypted = CryptoJS.AES.encrypt(decrypted, key);


    console.log(decrypted.toString(CryptoJS.enc.Utf8));
    console.log(encrypted);
