import {
  getUpyunAuth
} from "@/api/CommonServer.js"

/**
 * 全屏查看图片
 * @param {Number} current 选择下标
 * @param {Array} urls 链接地址数组
 */
export function previewImage(current, urls) {
  uni.previewImage({
    current,
    urls,
    longPressActions: {
      itemList: ['发送给朋友', '保存图片', '收藏'],
      success: data => {
        console.log('选中了第' + (data.tapIndex + 1) + '个按钮,第' + (data.index + 1) + '张图片');
      },
      fail: err => {
        console.log(err);
      }
    }
  })
}

/**
 * 上传又拍云图片
 * @param {Number} 选择图片数量
 */
export async function fnUploadUpyunPic(count = 1) {
  // 选择图片
  let [chooseImageErr, chooseImageRes] = await uni.chooseImage({
    count,
    sizeType: ['original'],
    sourceType: ['album', 'camera']
  });
  if (chooseImageErr) return false

  // 图片缓存路径
  let tempPath = chooseImageRes.tempFiles[0].path;

  // 请求的得到upyun上传授权
  let upyunRes = await getUpyunAuth({
    filesize: chooseImageRes.tempFiles[0].size,
    filetype: tempPath.slice(tempPath.lastIndexOf('.') + 1)
  })

  // 开始上传发送
  let [uploadFileErr, uploadFileRes] = await uni.uploadFile({
    url: upyunRes.data.data.url,
    filePath: tempPath,
    name: 'file',
    fileType: 'image',
    formData: {
      'authorization': upyunRes.data.data.authorization,
      'policy': upyunRes.data.data.policy
    },
    header: {
      'x-upyun-api-version': '2',
      'User-Agent': 'upyun-android-sdk 2.1.0',
      'Host': 'v0.api.upyun.com',
    }
  });
  if (uploadFileErr) return false

  // 得到结果字符
  return JSON.parse(uploadFileRes.data)
}
