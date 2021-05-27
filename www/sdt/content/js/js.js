var translitTable ={
    'à':'a',
    'á':'b',
    'â':'v',
    'ã':'g',
    'ä':'d',
    'å':'e',
    '¸':'yo',
    'æ':'zh',
    'ç':'z',
    'è':'i',
    'é':'y',
    'ê':'k',
    'ë':'l',
    'ì':'m',
    'í':'n',
    'î':'o',
    'ï':'p',
    'ð':'r',
    'ñ':'s',
    'ò':'t',
    'ó':'u',
    'ô':'f',
    'õ':'kh',
    'ö':'ts',
    '÷':'ch',
    'ø':'sh',
    'ù':'shch',
    'ü':'',
    'û':'y',
    'ú':'',
    'ý':'e',
    'þ':'yu',
    'ÿ':'ya',
    ' ':' '

};
var translit = function(input){

    var chars = _.str.chars(input);
//    alert(chars);
     var resultChars = _.map(chars, function(letter){
         var founded=undefined;

         if(_.has(translitTable,letter)){
//             alert(letter);
             founded=translitTable[letter];
//             alert(founded);
         }
         if(!_.has(translitTable,letter) && _.has(translitTable,letter.toLowerCase())){
             founded=_.str.capitalize(translitTable[letter.toLowerCase()]);
         }
//         alert(founded);
         return founded||'';
     });

    return resultChars.join('');
};

$(function(){

    var needKeyUp=false;
    if (!("oninput" in document.body)) {
        needKeyUp=true;
    }
   // alert(needKeyUp);
    $(document).on('input '+ (needKeyUp?' keyup ':''),'input.only-lat',function(e){
        var v = $(this).val();
        v = v.replace(/[^a-zA-Z -]/g, '');
        $(this).val(v);
      //  console.log(e.type);
    });

    $(document).on('input '+ (needKeyUp?' keyup ':''),'input.only-rus',function(e){
        var v = $(this).val();
        v = v.replace(/[^à-ÿÀ-ß¸¨ -]/g, '');
        $(this).val(v);
    //    console.log(e.type);

    });
});
