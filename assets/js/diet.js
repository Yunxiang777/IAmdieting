/* diet View JS */

/* diet-view-leftContent */
$(document).ready(function () {
    var config = {
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        }
    };
    var addMeal = `<button class="btn btn-outline-danger diet-view-addMeal" type="button" id="diet-view-addMeal">Add Meal</button>`;
    var searchDataElement = $('[data="diet-view-SearchData"]');
    var dietDropDown = $('#diet-view-dropDown');
    var inputvalidator = new InputValidator();

    /**
     * 食物與菜單搜尋
     */
    $("#diet-view-leftContent").on("click", ".diet-view-dropdown-item", function (event) {
        event.preventDefault();
        var liText = $(this).text();
        $("#diet-view-foodInput").attr("placeholder", liText);
        $("#diet-view-animateText").text(`Searching ${liText} results here...`);
        var isMenu = liText === 'Menu Analysis';
        var lesswidth = $(window).width() < 600;
        if (isMenu) {
            $(addMeal).insertBefore('#diet-view-searchFood');
            $('#diet-view-addMeal').toggleClass('width33', lesswidth);

        } else {
            $('#diet-view-addMeal').remove();
            $('.diet-view-mealCard').empty();
        }
        searchDataElement.add(dietDropDown).removeClass(isMenu ? 'width50' : 'width33').toggleClass(isMenu ? 'width33' : 'width50', lesswidth);
        searchDataElement.attr("id", isMenu ? "diet-view-searchMenu" : "diet-view-searchFood");
    });

    /**
     * 獲取食物營養資料
     */
    $("#diet-view-leftContent").on("click", "#diet-view-searchFood", () => {
        var food = $("#diet-view-foodInput").val();
        if (!inputvalidator.validateText(food)) {
            inputvalidator.displayError('diet-view-foodInput', '', 'Not valid!');
            return;
        }
        if (food) {
            axios.get(getFoodDataUrl + '/getFood/' + food)
                .then((response) => {
                    responseFoodData(response, 'Food');
                })
                .catch(function (error) {
                    console.error('Error fetching data:', error);
                });
        }
    });

    /**
     * 獲取菜單營養資料
     */
    $("#diet-view-leftContent").on("click", "#diet-view-searchMenu", () => {
        var meals = [];
        $("#diet-view .diet-view-addMealCard").each((index, element) => {
            meals.push($(element).text());
        });
        axios.post(getFoodDataUrl + '/getMeal', JSON.stringify({
            meals: meals
        }), config)
            .then((response) => {
                responseFoodData(response, 'Menu');
            })
            .catch(function (error) {
                console.error('Error fetching data:', error);
            });
    });

    /**
     * 新增菜單
     */
    $("#diet-view-leftContent").on("click", "#diet-view-addMeal", () => {
        var meal = $('#diet-view-foodInput').val();
        if (!meal) {
            inputvalidator.displayError('diet-view-foodInput', '', 'Not valid!');
            return;
        }
        var mealCard = `<div class='diet-view-addMealCard'>${meal}<i class="fa-solid fa-delete-left fa-lg"></i></div>`;
        $(".diet-view-mealCard").html($(".diet-view-mealCard").html() + mealCard);

    })

    /**
     * 刪除菜單
     */
    $("#diet-view-leftContent").on("click", ".fa-delete-left", (e) => {
        $(e.target).closest('.diet-view-addMealCard').remove();
    })

    /**
     * 菜單食譜模板
     * @param {object} response - 包含食譜數據的響應對象
     * @param {string} view - 視圖名稱
     */
    var responseFoodData = (response, view) => {
        var foods = response.data.result;
        if (foods.length !== 0) {
            var content = '<table class="table table-bordered" style="margin-bottom: 0;"> <tbody>';
            Object.keys(foods).forEach(function (key, index) {
                content += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${key}</td>
                    <td>${parseFloat(foods[key]).toFixed(1)}</td>
                </tr>
            `;
            });
            content += '</tbody></table>';
        } else {
            var content = "Couldn't find the corresponding " + view;
        }
        $("#diet-view-animateText").html(content);
    }

    //重置錯誤
    inputvalidator.resetError('#diet-view-foodInput');
    /**
     * RWD
     */
    var resizeElement = () => {
        var addMealExist = $('#diet-view-addMeal').length > 0;
        if ($(window).width() < 600) {
            $('#diet-view-foodInput').prependTo('.diet-view-searchFood');
            searchDataElement.toggleClass('width33', addMealExist).toggleClass('width50', !addMealExist);
            dietDropDown.toggleClass('width33', addMealExist).toggleClass('width50', !addMealExist);
            addMealExist ? $('#diet-view-addMeal').addClass('width33') : null;
        } else {
            $('#diet-view-foodInput').prependTo('.diet-view-foodBox');
            searchDataElement.add('#diet-view-dropDown, #diet-view-addMeal').removeClass('width50 width33');
        }
    }
    resizeElement();
    $(window).resize(() => {
        resizeElement();
    });
});

/* diet-view-rightContent */
$(document).ready(function () {
    var inputvalidator = new InputValidator();
    var config = {
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        }
    };
    var tdeeValue = $('#diet-view-rightContent-tdeeNum').text().replace('TDEE ', '');

    /**
     * 紀錄食物
     */
    $("#diet-view-rightContent").on("click", "#diet-view-rightContent-recordMeal", () => {
        var food = $("#diet-view-rightContent-recordFood").val();
        var calories = $("#diet-view-rightContent-recordCalories").val();
        if (!inputvalidator.validateText(food)) {
            inputvalidator.displayError('diet-view-rightContent-recordFood', '', 'Not valid!');
            return;
        }
        if (!inputvalidator.validateNatural(calories)) {
            inputvalidator.displayError('diet-view-rightContent-recordCalories', '', 'Not valid!');
            return;
        }
        axios.post(baseUrl + 'diet/recordMeal', JSON.stringify({
            food: food,
            calories: calories
        }), config)
            .then((response) => {
                if (response.data.result) {
                    var content = `
                    <tr>
                        <th scope="row">${response.data.result}</th>
                        <td>${food}</td>
                        <td class="diet-view-rightContent-tdCalories">${calories}</td>
                        <td>${response.data.date}</td>
                        <td class="diet-view-rightContent-icon">
                            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                data-bs-content="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </span>
                            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                data-bs-content="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </span>
                        </td>
                    </tr>`;
                    $('#diet-view-rightContent-table').append(content);
                }
                //計算TDEE
                dealTdee();
                setPopover();
            })
            .catch(function (error) {
                console.error('Error fetching data:', error);
            });
    });

    /**
     * 刪除紀錄
     */
    $("#diet-view-rightContent").on("click", ".fa-trash", (event) => {
        var $row = $(event.target).closest('tr');
        axios.post(baseUrl + 'diet/deleteMeal/' + $row.find('th').text(), {}, config)
            .then((response) => {
                if (response.data.result) {
                    $row.remove();
                }
                dealTdee();
            })
            .catch(function (error) {
                console.error('Error fetching data:', error);
            });
    })

    /**
     * 編輯紀錄
     */
    $("#diet-view-rightContent").on("click", ".fa-regular", (event) => {
        var row = $(event.target).closest('tr');
        var food = row.find('td:eq(0)');
        var calories = row.find('td:eq(1)');
        editMeal(food.text(), calories.text()).then(result => {
            if (result.isConfirmed) {
                callUpdateMealApi(food, calories, row.find('th').text(), result.value);
            }
        }).catch(error => {
            console.log("Cancel Edit", error);
        });
    });

    /**
     * 更新食物 API
     * 
     * @param {Object} food 食物元素
     * @param {Object} calories 卡路里元素
     * @param {string} id 食物ID
     * @param {Object} result 更新的结果
     */
    var callUpdateMealApi = (food, calories, id, result) => {
        axios.post(baseUrl + 'diet/updateMeal', JSON.stringify({
            id: id,
            food: result.food,
            calories: result.calories
        }), config)
            .then((response) => {
                if (response.data.result) {
                    food.text(result.food);
                    calories.text(result.calories);
                }
                //計算TDEE
                dealTdee();
            })
            .catch(function (error) {
                console.error('Error fetching data:', error);
            });
    }

    /**
     * 修改菜單
     * @param {string} food 食物名稱
     * @param {string} calories 卡路里
     * @returns {Promise} 
     */
    var editMeal = (food, calories) => {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: 'Edit Meal',
                html: `
                <input id="alert-food" class="swal2-input" placeholder="food" value="${food}">
                <input id="alert-calories" class="swal2-input" placeholder="calories" value="${calories}">
                `,
                focusConfirm: false,
                preConfirm: () => {
                    var food = $('#alert-food').val();
                    var calories = $('#alert-calories').val();
                    if (!food || !calories) {
                        Swal.showValidationMessage('Food name and Calories cannot be empty');
                    }
                    return { food: food, calories: calories };
                },
                confirmButtonText: 'Save Change'
            }).then((result) => {
                result.isConfirmed ? resolve(result) : reject(false);
            });
        });
    };

    /**
     * 計算TDEE
     */
    var dealTdee = () => {
        let totalCalories = 0;
        $('.diet-view-rightContent-tdCalories').each((index, element) => {
            let calories = parseFloat($(element).text());
            if (!isNaN(calories)) {
                totalCalories += calories;
            }
        });
        var tdeePercent = Math.round(totalCalories / tdeeValue * 100);
        tdeePercent = tdeePercent > 100 ? '100%' : tdeePercent + '%';
        $('.diet-view-rightContent-progress').css('width', tdeePercent).text(tdeePercent);
    }

    /* 
     *漂浮字效果 
     */
    var setPopover = () => {
        $('[data-bs-toggle="popover"]').each(function () {
            $(this).popover();
        });
    }
    //TDEE計算初始化
    dealTdee();
    //漂浮字初始化
    setPopover();
    //錯誤重置
    inputvalidator.resetError('#diet-view-rightContent-recordFood,#diet-view-rightContent-recordCalories');
})

/* 漂浮字效果 */
$(document).ready(function () {
    $('#diet-view-rightContent').on('click', '[data-bs-toggle="popover"]', () => {
        $('.popover ').remove();
    });
});