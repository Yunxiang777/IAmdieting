/**
 * callApi並處理回應的自訂類
 * 
 * @requires SweatAlert sweatalert
 */
class CallApi {

    constructor() {
        this.sweatalert = new SweatAlert();
    }

    /**
     * callApi結果
     * 
     * @param {string} url - 請求url
     * @param {object} objectField - 附帶檔案
     * @param {boolean} showLoading - 過場
     * @param {string} successTile - 成功標題
     * @param {string} successContent - 成功內容
     * @param {string} failedTitle - 失敗標題
     * @param {string} failedContent - 失敗內容
     * @param {boolean} reload - 網頁重整
     * @param {string} redirectUrl - 網頁轉址
     */
    checkApiResult(url, objectField, showLoading = '', successTile, successContent, failedTitle, failedContent, reload = false, redirectUrl = '') {
        var config = {
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            }
        };

        if (showLoading) {
            var load = this.sweatalert.showLoading(showLoading);
            config.onUploadProgress = load;
        }

        axios.post(url, JSON.stringify(objectField), config)
            .then((response) => {
                if (response.data.result === true) {
                    this.sweatalert.showNotification(successTile, successContent, true, 1000, false, reload, redirectUrl);
                } else {
                    this.sweatalert.showNotification(failedTitle, failedContent, false);
                }
            })
            .catch((error) => {
                this.sweatalert.showNotification('Error', 'Error submitting the form.', false, 1000, false);
                console.error('Error submitting the form.', error);
            });
        // 在箭頭函式 (=>) 中，this 會按照定義時的上下文進行綁定，因此在 then 和 catch 中使用箭頭函式可以確保 this.sweatalert 指向正確的物件。
    }

}
