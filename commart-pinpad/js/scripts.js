jQuery(document).ready(function($) {
    let phoneNumber = '';
    let passwordMap = {
        '0': ['A', '@', 'F', 'N', 'O', 'h'],
        '1': ['P', 'a', '!', 'w', 'B', 'o'],
        '2': ['k', 'Y', '#', 'm', 'R', 'x'],
        '3': ['L', '$', 'n', 'G', 't', '&'],
        '4': ['%', 'r', 'j', 'C', 'I', 'c'],
        '5': ['D', 'v', 'f', 'H', 'z', 'E'],
        '6': ['?', 'e', 'K', 'p', 's', '^'],
        '7': ['x', 'V', 'b', 'U', 'g', 'J'],
        '8': ['Z', 'q', 'M', 'd', 'l', '*'],
        '9': ['y', 'T', 'i', 'W', 'X', 'u']
    };
    let passwordChars = {};
    let isLoginMode = true;

    $('#custom-login-button, #custom-register-button').click(function() {
        $('#commart-pinpad').show();
        setTimeout(() => {
            showMessage('شماره موبایل خود را وارد کنید.');
        }, 2000);
    });

    $('.keypad .num').click(function() {
        if (phoneNumber.length < 11) {
            phoneNumber += $(this).text();
            $(this).css("color", "#FFFFFF");
            setTimeout(() => {
                $(this).css("color", "#727272");
            }, 200);
            updateDisplay();
        }
    });

    $('#backspace-button').click(function() {
        phoneNumber = '';
        passwordChars = {};
        updateDisplay();
    });

    $('#swap-button').click(function() {
        isLoginMode = !isLoginMode;
        swapMode();
    });

    function swapMode() {
        $('#pinpad-title').text(isLoginMode ? 'ورود' : 'ثبت نام');
        $('#commart-pinpad').css('background-color', isLoginMode ? '#375ffa' : '#212121');
        $('#commart-pinpad').animate({ transform: 'scale(0.85)' }, 100, function() {
            $(this).animate({ transform: 'scale(1.10)' }, 100, function() {
                $(this).animate({ transform: 'scale(1)' }, 100);
            });
        });
    }

    function updateDisplay() {
        $('#number-display').text(phoneNumber || '\xa0');
        $('#number-display').css('opacity', 0.5);
        setTimeout(() => {
            $('#number-display').css('opacity', 1);
        }, 200);

        if (phoneNumber.length === 11) {
            if (phoneNumber.startsWith('09')) {
                // Perform Ajax login or register check
                if (isLoginMode) {
                    ajaxLoginCheck(phoneNumber, generatePassword(phoneNumber));
                } else {
                    ajaxRegisterCheck(phoneNumber, generatePassword(phoneNumber));
                }
            } else {
                triggerWrongAnimation();
                showMessage('شماره معتبر نیست.', 3000);
            }
        }
    }

    function generatePassword(phoneNumber) {
        let password = '';
        let digitCounts = {};

        for (let i = 0; i < phoneNumber.length; i++) {
            const digit = phoneNumber[i];
            if (!digitCounts[digit]) {
                digitCounts[digit] = 0;
            }
            const charIndex = digitCounts[digit] % passwordMap[digit].length;
            password += passwordMap[digit][charIndex];
            digitCounts[digit]++;
        }
        return password;
    }

    function ajaxLoginCheck(username, password) {
        $.ajax({
            type: 'POST',
            url: commartPinpad.ajax_url,
            data: {
                action: 'custom_ajax_login',
                username: username,
                password: password,
            },
            success: function(response) {
                if (response.success) {
                    $('#number-display').addClass('correct');
                    setTimeout(() => {
                        $('#number-display').removeClass('correct');
                        $('#login-form form').submit();
                    }, 1000);
                } else {
                    triggerWrongAnimation();
                    showMessage('شماره موجود نیست. ثبت نام کنید.', 3000);
                }
            }
        });
    }

    function ajaxRegisterCheck(username, password) {
        $.ajax({
            type: 'POST',
            url: commartPinpad.ajax_url,
            data: {
                action: 'custom_ajax_register',
                username: username,
                password: password,
            },
            success: function(response) {
                if (response.success) {
                    $('#number-display').addClass('correct');
                    setTimeout(() => {
                        $('#number-display').removeClass('correct');
                        $('#register-form form').submit();
                    }, 1000);
                } else {
                    triggerWrongAnimation();
                    showMessage('شماره موجود است. لطفا وارد شوید.', 3000);
                }
            }
        });
    }

    function triggerWrongAnimation() {
        $('#number-display').removeClass('ajax-wait').addClass('wrong');
        $('#commart-pinpad').addClass('wrong-bg');
        setTimeout(() => {
            $('#number-display').removeClass('wrong');
            $('#commart-pinpad').removeClass('wrong-bg');
            removeDigitsOneByOne();
        }, 1000);
    }

    function removeDigitsOneByOne() {
        if (phoneNumber.length > 0) {
            phoneNumber = phoneNumber.slice(0, -1);
            updateDisplay();
            setTimeout(removeDigitsOneByOne, 100);
        }
    }

    function showMessage(text, duration = 3000) {
        clearTimeout($('#message')[0].timeout);
        $('#message').text(text).addClass('visible');
        $('#message')[0].timeout = setTimeout(() => {
            $('#message').removeClass('visible');
        }, duration);
    }
});
