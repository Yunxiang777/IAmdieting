/* bmr View JS */
$(document).ready(function () {
    var inputvalidator = new InputValidator();
    var bodydata = new BodyData();

    // countBmr觸發
    $('.bmr-view').on('click', '#bmr-countBmr', function () {
        var age = $("#bmr-age").val();
        var gender = $("#bmr-gender").val();
        var height = $("#bmr-height").val();
        var weight = $("#bmr-weight").val();

        // 驗證age格式
        if (!inputvalidator.validateInt(age)) {
            inputvalidator.displayError('bmr-age', 'age');
            return;
        }

        // 驗證height格式
        if (!inputvalidator.validateFloat(height)) {
            inputvalidator.displayError('bmr-height', 'height');
            return;
        }

        // 驗證weight格式
        if (!inputvalidator.validateFloat(weight)) {
            inputvalidator.displayError('bmr-weight', 'weight');
            return;
        }

        //計算BMR結果
        $("#bmr-bmrResult").html(`Basal metabolic rate (BMR) calculation: <div class="bmr">${bodydata.calculateBMR(age, gender, height, weight).toFixed(2)}</div> calories per day`);
    });
    inputvalidator.resetError("#bmr-age, #bmr-height, #bmr-weight");
});

/* calorieLookup View JS */
$(document).ready(function () {
    var inputvalidator = new InputValidator();
    var sweatalert = new SweatAlert();

    //search觸發
    $('.calorieLookup-view').on('click', '#calorieLookup-search', function () {
        var foodName = $("#calorieLookup-foodName").val();
        if (!inputvalidator.validateText(foodName)) {
            inputvalidator.displayError('calorieLookup-foodName', 'foodName')
            return;
        } else {
            $.ajax({
                url: $(this).data().calorielookupcontrollergetfooddata,
                type: 'post',
                contentType: 'application/json; charset=utf-8',
                data: JSON.stringify({
                    'foodName': foodName
                }),
                dataType: 'json',
                success: function (res) {
                    var result = res.result;
                    var title = 'Food : ' + foodName;
                    var className = 'calorieLookup-custom-swal-button';
                    var confirmButtonText = 'Confirm';
                    if (result) {
                        var content = `
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CHOCDF</td>
                                    <td>${result.CHOCDF}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>ENERC_KCAL</td>
                                    <td>${result.ENERC_KCAL}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td >FAT</td>
                                    <td>${result.FAT}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>FIBTG</td>
                                    <td>${result.FIBTG}</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>PROCNT</td>
                                    <td>${result.PROCNT}</td>
                                </tr>
                            </tbody>
                        </table>
                        `;
                    } else {
                        var content = `<p>Cannot find corresponding food information.</p>`;
                    }
                    sweatalert.useCustomSwalAlertButton(title, content, className, confirmButtonText);
                },
                error: function () {
                    alert('Error submitting the form.');
                }
            });
        }
    })
    inputvalidator.resetError("#calorieLookup-foodName");
})

/* mealCard-view View JS */
$(document).ready(function () {
    //cropper裁剪樣式
    var customizecropper = new CustomizeCropper('.mealCard-view', '#mealCard-img-camera', 'mealCard', '#mealCard-img');
    customizecropper.useRectangle(840, 630);
})