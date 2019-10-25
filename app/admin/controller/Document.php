<?php
/**
 * Description of Document.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/9 16:22
 */

namespace app\admin\controller;

use app\admin\service\{
    FileService,UeditorService,DocumentService,CategoryService
};
use app\facade\UserInfo;
use app\admin\traits\{
    Jump,Admin
};
use think\facade\View;

class Document
{
    use Jump,Admin;

    /**
     * 初始化
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function initialize()
    {
        $this->categoryMenu();
    }

    /**
     * 文档首页
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        $param=$this->app->request->param();
        $param['metaTitle']='文章列表';
        return $this->setView($param);
    }

    /**
     * 我的文章
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function myDocument()
    {
        return $this->setView(['metaTitle' => '我的文章'], 'my_document');
    }


    /**
     * 文档接口
     * @author staitc7 <static7@qq.com>
     * @param DocumentService $documentService
     * @return mixed
     * @throws DbException
     */
    public function documentInterface(DocumentService $documentService)
    {
        $param=$this->app->request->params([
            'status'=>['status','='],
            'title'=>['title','like'],
            'category_id'=>['category_id','='],
            'create_time'=>['create_time','between time','~']
        ]);
        $data=$documentService->getDocumentListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 文档接口
     * @author staitc7 <static7@qq.com>
     * @param DocumentService $documentService
     * @return mixed
     * @throws DbException
     */
    public function myDocumentInterface(DocumentService $documentService)
    {
        $param=$this->app->request->params([
            'status'=>['status','='],
            'title'=>['title','like'],
            'create_time'=>['create_time','between time','~']
        ]);

        $data=$documentService->getDocumentListPage(array_merge([
            'user_id'=>['user_id', '=', UserInfo::getUserId()],
            'status' => ['status', 'in', [0, 1, 2]]
        ],$param));
        return $this->layuiJson($data->toArray());
    }

    /**
     * 待审核
     * @author staitc7 <static7@qq.com>
     */
    public function examine()
    {
        return $this->setView(['metaTitle' => '待审核']);
    }

    /**
     * 回收站
     * @author staitc7 <static7@qq.com>
     */
    public function recycle()
    {
        return $this->setView(['metaTitle' => '回收站']);
    }

    /**
     * 物理删除
     * @author staitc7 <static7@qq.com>
     * @param DocumentService $documentService
     * @return mixed
     * @throws DbException
     */
    public function physicalDelete(DocumentService $documentService)
    {
        $param = $this->app->request->param();
        $data=$documentService->physicalDelete($param);
        if ($data=== false) {
           return $this->error('删除失败') ;
        }
        return $this->success('删除成功');
    }

    /**
     * 创作中的文章
     * @author staitc7 <static7@qq.com>
     * @param DocumentService $documentService
     * @return mixed
     * @throws DbException
     */
    public function creativeWorkInterface(DocumentService $documentService)
    {
        $param=$this->app->request->params([
            'status'=>['status','='],
            'draft'=>['draft','='],
            'check'=>['check','='],
        ]);
        $data=$documentService->getCreativeWorkListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 草稿箱
     * @author staitc7 <static7@qq.com>
     */
    public function draftBox()
    {
        return $this->setView(['metaTitle' => '草稿箱'],'draft_box');
    }

    /**
     * 设置状态
     * @param DocumentService $documentService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setStatus(DocumentService $documentService)
    {
        $param = $this->app->request->param();
        $info  = $documentService->setStatus($param);
        if ($info === false) {
            return $this->error($documentService->getError());
        }
        $this->app->cache->delete('category_list');
        return $this->success('更新成功');
    }


    /**
     * 分类菜单
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    private function categoryMenu()
    {
        $param= $this->app->request->only(['category_id'=>0]);
        $categoryService = new CategoryService();
        $data=$categoryService->categoryMenu($param);
        $this->initView();
        View::assign(['child'=>$data]);
        return true;
    }

    /**
     * 文章详情
     * @param DocumentService $documentService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function edit(DocumentService $documentService)
    {
        $param=$this->app->request->param();
        $data=$documentService->edit($param);
        return $this->setView([
            'info' => $data ?? null,
            'category_id' => $param['category_id'] ?? 0,
            'metaTitle' => (isset($param['id']) === true && (int)$param['id'] > 0) ? '编辑文章' : '新增文章'
        ]);
    }


    /**
     * 上传图片
     * @param FileService $fileService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function documentPicture(FileService $fileService)
    {
        $param=$this->app->request->only(['field'=>'documentPicture']);
        $data=$fileService->pictureUpload($param);
        if ($data === false){
            return $this->error($fileService->getError());
        }
        return $this->success('上传成功!', '', $data);
    }

    /**
     * 富文本上传附件
     * @param FileService $fileService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function documentFile(FileService $fileService)
    {
        $param=$this->app->request->only(['field'=>'upfile']);
        $data=$fileService->fileUpload($param);
        if ($data === false){
            return $this->error($fileService->getError());
        }
        if ($data === false) {
            return $this->json([
                "state"    => "failure",
                "url"      => '',
                "original" => '',
                "title"    => $fileService->getError(),
                'msg'      => $fileService->getError()
            ]);
        }
        return $this->json([
            "state"    => "SUCCESS",
            "url"      => $data['url'] ?? $data['path'],
            "original" => $data['original_name'],
            "title"    => $data['original_name'],
            'id'       => $data['id'],
            'msg'      => '上传成功'
        ]);
    }

    /**
     * 富文本编辑器
     * @author staitc7 <static7@qq.com>
     * @param UeditorService $ueditorService
     * @return mixed
     */
    public function ueditorCheck(UeditorService $ueditorService)
    {
        $param=$this->app->request->param();
        $data=$ueditorService->action($param);
        if ($data === false){
            return $this->json(['title'=> '请求地址出错','state'=>'failure']);
        }
        return $this->json($data);
    }

    /**
     * 富文本上传图片
     * @param FileService $fileService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function picture(FileService $fileService)
    {
        $param = $this->app->request->only(['field' => 'picture']);
        $data  = $fileService->pictureUpload($param);
        if ($data === false) {
            return $this->json([
                "state"    => "failure",
                "url"      => '',
                "original" => '',
                "title"    => $fileService->getError()
            ]);
        }
        return $this->json([
            "state"    => "SUCCESS",
            "url"      => $data['url'] ?? $data['path'],
            "original" => $data['original_name'],
            "title"    => $data['original_name']
        ]);
    }

    /**
     * 提交审核
     * @author staitc7 <static7@qq.com>
     * @param DocumentService $documentService
     * @return mixed
     */
    public function censor(DocumentService $documentService)
    {
        $param=$this->app->request->param();
        $data=$documentService->censor($param);
        if ($data === false) {
            return $this->error($documentService->getError());
        }
        return $this->success('操作成功');
    }

    /**
     * 通过审核
     * @param DocumentService $documentService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function approved(DocumentService $documentService)
    {
        $param=$this->app->request->param();
        $data=$documentService->approved($param);
        if ($data === false) {
            return $this->error($documentService->getError());
        }
        $this->app->cache->delete('category_list');
        return $this->success('操作成功');
    }

    /**
     * 添加或者更新
     * @param DocumentService $documentService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function renew(DocumentService $documentService)
    {
        $param=$this->app->request->param();
        //提交 草稿字段设置为0
        $param['draft']=0;
        $data=$documentService->renew($param);
        if ($data === false) {
            return $this->error($documentService->getError());
        }

        $this->app->cache->delete('category_list');
        $url=$this->app->route->buildUrl('Document/index', ['category_id' => $data->category_id]);
        return $this->success('操作成功',$url);
    }

    /**
     * 草稿-自动保存
     * @param DocumentService $documentService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function autoSave(DocumentService $documentService)
    {
        $param=$this->app->request->param();
        $data=$documentService->autoSave($param);
        if ($data === false) {
            return $this->result('', 10, $documentService->getError());
        }
        return $this->result($data->id, 0, '草稿保存成功');
    }

    /**
     * 移动
     * @param DocumentService $documentService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function move(DocumentService $documentService)
    {
        $param = $this->app->request->param();
        $documentService->move($param);
        $this->app->cache->delete('category_list');
        return $this->success('请选择要移动到的分类！', '');
    }

    /**
     * 移动
     * @param DocumentService $documentService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function copy(DocumentService $documentService)
    {
        $param = $this->app->request->param();
        $documentService->copy($param);
        $this->app->cache->delete('category_list');
        return $this->success('请选择要复制到的分类！', '');
    }

    /**
     * 粘贴
     * @param DocumentService $documentService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function paste(DocumentService $documentService)
    {
        $param = $this->app->request->param();
        $documentService->paste($param);
        $this->app->cache->delete('category_list');
        return $this->success('操作成功！');
    }
}