<div class="wrap">
     <h2>アップロードフォームの作成</h2>
     <div>
       <form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] );?>">
         <table class="form-table">
           <tbody>

             <tr class="form-field form-required term-name-wrap">
               <th scope="row">
                 <label for="n5uf_name">フォーム名</label>
               </th>
               <td>
                 <input type="text" name="n5uf_name" id="n5uf_name">
                 <p class="description">フォーム管理名称です。フォーム一覧などで利用されます。他のフォームと区別しやすい名称を設定してください。管理画面以外では利用されません。</p>
               </td>
             </tr>

             <tr class="form-field form-required term-name-wrap">
               <th scope="row">
                 <label for="n5uf_directory">アップロードディレクトリ</label>
               </th>
               <td>
                 <input type="text" name="n5uf_directory" id="n5uf_directory">
                 <p class="description">アップロードされたファイルの出力先です。</p>
               </td>
             </tr>

             <tr class="form-field form-required term-name-wrap">
               <th scope="row">
                 <label for="n5uf_ext">拡張子フィルタ</label>
               </th>
               <td>
                 <input type="text" name="n5uf_ext" id="n5uf_ext">
                 <p class="description">アップロードされるファイルを拡張子で制限することが出来ます。複数指定する場合はカンマで区切って登録してください。</p>
               </td>
             </tr>

             <tr class="form-field form-required term-name-wrap">
               <th scope="row">
                 <label for="n5uf_mime">MIMEタイプフィルタ</label>
               </th>
               <td>
                 <input type="text" name="n5uf_mime" id="n5uf_mime">
                 <p class="description">アップロードされるファイルをMIMEタイプで制限することが出来ます。複数指定する場合はカンマで区切って登録してください。</p>
               </td>
             </tr>

             <tr class="form-field form-required term-name-wrap">
               <th scope="row">
                 <label for="n5uf_adminnotice">通知先</label>
               </th>
               <td>
                 <input type="text" name="n5uf_adminnotice" id="n5uf_adminnotice">
                 <p class="description">通知先メールアドレスを入力してください。</p>
               </td>
             </tr>

             <tr class="form-field form-required term-name-wrap">
               <th scope="row">
                 <label for="n5uf_usernotice">ユーザ通知有無</label>
               </th>
               <td>
                 <select name="n5uf_usernotice" id="n5uf_usernotice">
                   <option value="0" selected="selected">なし</option>
                   <option value="1" selected="selected">あり</option>
                 </select>
                 <p class="description">アップロードユーザへの通知有無の設定です。"あり"に設定した場合はメールアドレス入力用のフォームが生成されます。</p>
               </td>
             </tr>


           </tbody>
         </table>

         <?php submit_button('作成');?>
       </form>

     
     </div>
</div>