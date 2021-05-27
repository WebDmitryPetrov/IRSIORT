var translitTable ={
    '�':'a',
    '�':'b',
    '�':'v',
    '�':'g',
    '�':'d',
    '�':'e',
    '�':'yo',
    '�':'zh',
    '�':'z',
    '�':'i',
    '�':'y',
    '�':'k',
    '�':'l',
    '�':'m',
    '�':'n',
    '�':'o',
    '�':'p',
    '�':'r',
    '�':'s',
    '�':'t',
    '�':'u',
    '�':'f',
    '�':'kh',
    '�':'ts',
    '�':'ch',
    '�':'sh',
    '�':'shch',
    '�':'',
    '�':'y',
    '�':'',
    '�':'e',
    '�':'yu',
    '�':'ya',
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
        v = v.replace(/[^�-��-߸� -]/g, '');
        $(this).val(v);
    //    console.log(e.type);

    });
});
