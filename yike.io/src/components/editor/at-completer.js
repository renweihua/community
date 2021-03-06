import http from '$utils/http'
import CodeMirror from 'codemirror'

require('codemirror/addon/hint/show-hint.css')
require('codemirror/addon/hint/show-hint')
require('codemirror/addon/hint/html-hint')

let emojiCompleter = cm => {
  let pageUsers = window['pageUsers'] || []

  CodeMirror.showHint(
    cm,
    async () => {
      let cur = cm.getCursor()
      let token = cm.getTokenAt(cur)
      let start = token.start
      let end = cur.ch
      let line = cur.line
      let currentWord = token.string

      if (currentWord.indexOf('@') !== 0) {
        return false
      }

      let mapToList = (users = []) => {
        if (users.length <= 0) {
          return [];
        }
        pageUsers.concat(users).forEach(user => {
          if (!results.some(o => o.text === user.user_info.nick_name + ' ')) {
            results.push({
              text: user.user_info.nick_name + ' ',
              render (element) {
                element.innerHTML = `<img style="width:1.2em;height: 1.2em;" src="${user.avatar}" alt="${user.name}" async > ${
                  user.user_info.nick_name
                }`
              }
            })
          }
        })

        return results
      }

      if (currentWord === '@' && pageUsers.length > 0) {
        return {
          list: mapToList(),
          from: CodeMirror.Pos(line, start + 1),
          to: CodeMirror.Pos(line, end)
        }
      }

      if (currentWord.length >= 2) {
        let users = await http.get('users?query=' + currentWord.substring(1));

        if (users.data.data.length >= 1) {
          let filtered = mapToList(users.data.data)

          return {
            list: filtered,
            from: CodeMirror.Pos(line, start + 1),
            to: CodeMirror.Pos(line, end)
          }
        }
      } else {
        return false
      }
    },
    { completeSingle: false }
  )
}

export default emojiCompleter
