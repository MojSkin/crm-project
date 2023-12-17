// import persianJS from 'persianjs'
import moment from "moment-jalaali";
import { toast } from 'vue3-toastify';

export default {
    stripHtml(html) {
        let tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        const stripped = tmp.textContent || tmp.innerText || ""
        // document.remove(tmp)
        return stripped;
    },

    humanReadableFileSize(size) {
        let result = ''
        size = parseInt(size)
        const units = ['بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت']
        if (typeof size=='number' && !isNaN(size)) {
            let i = 0
            while (size >= 1024 && i < units.length) {
                size = (size/1024)
                i++
            }
            result = size.toFixed(2)+' '+units[i]
        } else {
            result ='صفر بایت'
        }
        return result
    },

    showHidePassword(el) {
        let elm = document.getElementById(el)
        if (elm) {
            elm.type = elm.type === 'password' ? 'text' : 'password'
            return true
        }
        return false
    },

    getPasswordIcon(el, cls='fa-eye-slash') {
        let elm = document.getElementById(el)
        let class_ = cls
        if (elm) {
            class_ = elm.type === 'password' ? 'fa-eye-slash' : 'fa-eye'
        }
        return class_
    },

    csrf() {
        return document.head.querySelector('meta[name="csrf-token"]').content;
    },

    makeAbbr(name) {
        const userName = name.split(' ');
        return userName[0][0] + (userName.length > 1 ? ' ' + userName[userName.length - 1][0] : '')
    },

    screenSize(width = null) {
        let screenSize = null
        if (window.matchMedia("only screen and (max-width: 320px)").matches) {
            return 'XS'
        } else if (window.matchMedia("only screen and (min-width: 321px) and (max-width: 480px)").matches) {
            return 'SM'
        } else if (window.matchMedia("only screen and (min-width: 481px) and (max-width: 768px)").matches) {
            return 'MD'
        } else if (window.matchMedia("only screen and (min-width: 769px) and (max-width: 1024px)").matches) {
            return 'LG'
        } else if (window.matchMedia("only screen and (min-width: 1025px) and (max-width: 1200px)").matches) {
            return 'XL'
        }
        // (window.matchMedia("only screen and (min-width: 1201px)").matches)
        return 'XXL'
    },

    jDate(date = Date.now(), format = 'jYYYY/jMM/jDD', fromFormat = null) {
        if (fromFormat) {
            return date ? moment?.from(date, 'en', fromFormat)?.locale("fa")?.format(format) || '-' : '-'
        } else {
            return date ? moment(date)?.locale("fa")?.format(format) || '-' : '-'
        }
    },

    jDateNow(date = Date.now()) {
        return date ? moment(date)?.locale("fa")?.fromNow() || '-' : '-'
    },

    arabicChar: function (st) {
        return persianJs(st.tosting()).arabicChar().tosting()
    },

    persianNumber: function (st) {
        return persianJs(st.tosting()).persianNumber().tosting()
    },

    arabicNumber: function (st) {
        return persianJs(st.tosting()).arabicNumber().tosting()
    },

    englishNumber: function (st) {
        return persianJs(st.tosting()).englishNumber().tosting()
    },

    toEnglishNumber: function (st) {
        return persianJs(st.tosting()).toEnglishNumber().tosting()
    },

    fixURL: function (st) {
        return persianJs(st.tosting()).fixURL().tosting()
    },

    decodeURL: function (st) {
        return persianJs(st.tosting()).decodeURL().tosting()
    },

    switchKey: function (st) {
        return persianJs(st.tosting()).switchKey().tosting()
    },

    digitsToWords: function (st) {
        return persianJs(st.tosting()).digitsToWords().tosting()
    },

    halfSpace: function (st) {
        return persianJs(st.tosting()).halfSpace().tosting()
    },

    console_log() {
        for (let i = 0; i < arguments.length; i++) {
            console.log(arguments[i])
        }
    },

    compare(side_a, side_b, a_side_is_bigger) {
        if (a_side_is_bigger) {
            return side_a - side_b
        } else {
            return side_b - side_a
        }
    },

    copyToClipboard(text) {
        $('body').append('<input id="thisIsMyTempInput" value="' + text + '">')
        let copyText = document.getElementById("thisIsMyTempInput");
        copyText.select()
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        document.execCommand('copy')
        copyText.remove()
    },

    responseTime(seconds) {
        let days = Math.floor((seconds % 31536000) / 86400);
        let ext_min = Math.floor(((seconds % 31536000) % 86400) / 60);
        let hours = Math.floor(((seconds % 31536000) % 86400) / 3600);
        let minutes = Math.floor((((seconds % 31536000) % 86400) % 3600) / 60);
        let sec = (((seconds % 31536000) % 86400) % 3600) % 60;
        let result = ''
        if (days >= 30) {
            result = 'بیشتر از یک ماه'
        } else {
            if (days >= 7) {
                result = 'بیشتر از یک هفته'
            } else {
                if (days > 1 || (days == 1 && ext_min > 0)) {
                    result = 'بیشتر از یک روز'
                } else {
                    if (hours > 2 || (hours == 2 && (minutes > 0 || sec > 0))) {
                        result = 'حدود ' + hours + ' ساعت'
                    } else {
                        if (hours == 0) {
                            if (minutes >= 45) {
                                result = 'کمتر از یک ساعت'
                            } else if (minutes >= 30 || minutes < 45) {
                                minutes = 45
                            } else if (minutes >= 15 || minutes < 30) {
                                minutes = 30
                            } else if (minutes >= 5 || minutes < 15) {
                                minutes = 15
                            } else {
                                minutes = 5
                            }
                            result = 'کمتر از ' + minutes + ' دقیقه'
                        } else {
                            result = 'بین یک الی دو ساعت'
                        }
                    }
                }
            }
        }
        return result
    },

    notify(title, message, user_options = {}) {
        const options = {
            multiple                : user_options.multiple ?? true,
            limit                   : user_options.limit ?? 5,
            newestOnTop             : user_options.newestOnTop ?? false,
            dangerouslyHTMLString   : user_options.html ?? true,
            icon                    : user_options.icon ?? undefined,
            rtl                     : user_options.rtl ?? true,
            position                : user_options.position ?? toast.POSITION.BOTTOM_LEFT,
            autoClose               : user_options.autoClose ?? 5000,
            closeButton             : user_options.closeButton ?? true,
            transition              : user_options.transition ?? toast.TRANSITIONS.BOUNCE,
            hideProgressBar         : user_options.hideProgressBar ?? false,
            pauseOnHover            : user_options.pauseOnHover ?? true,
            pauseOnFocusLoss        : user_options.pauseOnFocusLoss ?? false,
            closeOnClick            : user_options.closeOnClick ?? true,
            theme                   : user_options.theme ?? 'auto',
            type                    : user_options.type ?? 'info',
            title                   : title,
        }
        let htmlMessage
        if (message === undefined || message === null || message?.length === 0) {
            htmlMessage = '<p class="is-weight-400 is-size-6 has-text-justified mb-0">'+title+'</p>'
        } else {
            htmlMessage = '<div class="is-weight-700 is-size-6 is-font-alt">'+title+'</div><hr class="mt-0 mb-2"><p class="is-weight-400 is-size-6 has-text-justified mb-0">'+message+'</p>'
        }
        return toast(htmlMessage, options)
    },

    hideMeta(meta = '', starChr = '*', starsLength = 4) {
        const stars = starChr.repeat(starsLength)
        let start = 3;
        let end = meta.length - 3
        if (this.isEmail(meta)) {
            start = start >= meta.indexOf('@') ? meta.indexOf('@') : start;
            end = meta.length - 3;
            end = end <= start ? meta.length-1 : end;
        }
        let result = meta.subst(0, start) + stars + meta.subst(end);
        return result;
    },

    username(username) {
        let regex = /^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/;
        return regex.test(username);
    },

    isEmail(email) {
        let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    },

    isMobile(number) {
        number = String(number)
        let regex = /(\+989|00989|989|09|9)(0|1|2|3|4|5|9)\d{8}/;
        // const num = number.substring(number.length - 10)
        // const area_code = num.substring(0, 2)
        // const region = number.replace(num, '')
        // return (num.length === 10 && ['90', '91', '92', '93', '94', '95', '99'].includes(area_code) && ['+98', '98', '0098', ''].includes(region))
        return regex.test(number)
    },

    isSafePassword(password, length = 6) {
        // let regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/;

        const numX = /\d/
        const ultX = /[A-Z]/
        const lltX = /[a-z]/
        const spcX = /[!@#$%^&*()]/

        const criteria = {
            ln: password.length >= length,
            uc: (ultX.test(password)),
            lc: (lltX.test(password)),
            nm: (numX.test(password)),
            sc: (spcX.test(password)),
        };

        return {score: Object.values(criteria).filter(Boolean).length, ...criteria}
    },

    isValidNationalId(code) {
        let st = String(code);

        // validate the string length and value
        let stLen = st.length
        let stVal = parseInt(st);
        if ( stLen < 8 || stLen > 10 || isNaN(stVal) || stVal == 0 ) {
            return false;
        }

        while ( st.length < 10 ) {
            st = '0' + st;
        }

        if (st.match(/^(\d)\1+$/g)){
            return false;
        }

        let checkDigit = parseInt(st.slice(-1))
        st = st.slice(0, -1); // remove the check digit
        let sum = 0
        for (let i = 0; i < st.length; i++) {
            console.log((10-i), st.substring(i, i+1))
            sum += (10-i) * st.substring(i, i+1);
        }

        let mod = sum % 11;

        return ( mod < 2 && mod === checkDigit ) || ( mod >= 2 && mod + checkDigit === 11);
    },

    priceFormat(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    },

    toDataURL(src, callback) {
        let image = new Image();
        image.crossOrigin = 'Anonymous';
        image.onload = function(){
            let canvas = document.createElement('canvas');
            let context = canvas.getContext('2d');
            canvas.height = this.naturalHeight;
            canvas.width = this.naturalWidth;
            context.drawImage(this, 0, 0);
            let dataURL = canvas.toDataURL('image/jpeg');
            callback(dataURL);
        };
        image.src = src;
    },
}
