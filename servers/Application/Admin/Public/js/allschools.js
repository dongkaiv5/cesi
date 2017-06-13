/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
        function getall(url){
            art.dialog({
                        id: 'bef',
                        lock:'true',
                        content: "正在查询，请稍候",
                        cancelVal: '关闭'
                     });
            $.get(url,function(data){
                if(art.dialog.list["bef"]){
                    art.dialog.list["bef"].close();
                }
                    art.dialog({
                        id: 'listinfo',
                        lock:'true',
                        goldenRatio:"50%",
                        align:"center",
                        content: data,
                        cancelVal: '关闭',
                        lock:true
                     });
            });
        }
        function get_info(t){
                arr = new Array();
                arr["org_name"] = $(t).find("p").html();
                arr["org_id"] = $(t).find("span").html(); 
                if(art.dialog.list["listinfo"]){
                    art.dialog.list["listinfo"].close();
                }
                assignment();
        }
        function assignment(){
            $("#org_name").val(arr["org_name"]);
            $("#org_id").val(arr["org_id"]);
        }
        


