<?php

namespace App\Admin\Controllers\Sms;

use App\Models\SmsCache;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SmsCacheAdminController extends Controller
{
    use HasResourceActions;

    public $title = '短信验证码管理';

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header($this->title)
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header($this->title)
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header($this->title)
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header($this->title)
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SmsCache);

        $grid->model()->latest();

        $grid->id('Id');
        $grid->scene('场景值');
        $grid->phone('手机号');
        $grid->code('验证码');
        $grid->expired_at('到期时间');
        $grid->created_at('发送时间');

        $grid->disableExport();
        $grid->disableActions();
        $grid->disableCreateButton();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(SmsCache::findOrFail($id));

        $show->id('Id');
        $show->scene('Scene');
        $show->phone('Phone');
        $show->code('Code');
        $show->expired_at('Expired at');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->deleted_at('Deleted at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SmsCache);

        $form->text('scene', 'Scene');
        $form->mobile('phone', 'Phone');
        $form->text('code', 'Code');
        $form->datetime('expired_at', 'Expired at')->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
