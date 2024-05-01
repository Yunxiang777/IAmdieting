/**
 * 日期格式化工具類
 * 
 */
class FormatDate {

    /**
     * 根據給定的數字返回相對於今天的日期
     * @param {number} num - 數字表示的日期偏移量。如果為正數，則代表未來的日期；如果為負數，則代表過去的日期；如果為0，則代表今天的日期。
     * @returns {string} - 返回格式為 YYYY-MM-DD 的日期字符串
     */
    getFormatDate(num = 0) {
        var today = new Date();
        var customDate = new Date(today);
        customDate.setDate(today.getDate() + num);
        var year = customDate.getFullYear();
        var month = String(customDate.getMonth() + 1).padStart(2, '0');
        var day = String(customDate.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

}