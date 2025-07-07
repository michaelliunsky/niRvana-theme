<?php
add_action('rest_api_init', function () {
    register_rest_route('pandastudio/framework', '/get_option/', array(
        'methods' => 'POST',
        'callback' => 'get_option_by_RestAPI',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('pandastudio/framework', '/update_option/', array(
        'methods' => 'POST',
        'callback' => 'update_option_by_RestAPI',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
    register_rest_route('pandastudio/framework', '/wp_query/', array(
        'methods' => 'POST',
        'callback' => 'wp_query_by_RestAPI',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
});

function get_option_by_RestAPI($data)
{
    $dataArray = json_decode($data->get_body(), true);
    if (!is_array($dataArray) || count($dataArray) < 1) {
        return array('error' => '数据格式不正确或为空！');
    }
    $return = array();
    foreach ($dataArray as $option_name => $value) {
        $return[$option_name] = get_option($option_name) ? get_option($option_name) : "";
    }
    return $return;
}

function update_option_by_RestAPI($data)
{
    if (current_user_can('manage_options')) {
        $dataArray = json_decode($data->get_body(), true);
        foreach ($dataArray as $option_name => $value) {
            update_option($option_name, $value);
        }
        return array('state' => true);
    } else {
        return array('state' => false, 'error' => 'PANDA Studio framework 无法执行此操作，原因：您没有进行此操作的权限');
    }
}

function wp_query_by_RestAPI($data)
{
    $dataArray = json_decode($data->get_body(), true);
    if (current_user_can('manage_options')) {
        $keyword = $dataArray['keyword'];
        if (gettype($keyword) == 'integer') {
            $args = array('post_type' => 'any', 'p' => $keyword);
        } else {
            $args = array('post_type' => 'any', 's' => $keyword);
        }
        $query = new WP_Query($args);
        $result = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $result[] = array('label' => get_the_title(), 'value' => get_the_ID());
            }
            wp_reset_postdata();
        }
        return $result;
    } else {
        return array('label' => '无权限', 'value' => '0');
    }
}

add_action('admin_menu', 'add_option_json_page');
function add_option_json_page()
{
    add_menu_page(
        'setting',
        '主题设置',
        'manage_options',
        'pandastudio_framework_options',
        'pandastudio_framework_create_json_option_page',
        'dashicons-admin-customizer',
        60
    );
}

function pandastudio_framework_create_json_option_page()
{
    wp_enqueue_media();
    $adminColor = get_user_meta(get_current_user_id(), 'admin_color', true);
    $supportColorArray = array('blue', 'coffee', 'ectoplasm', 'fresh', 'light', 'midnight', 'ocean', 'sunrise');
    $color = in_array($adminColor, $supportColorArray) ? $adminColor : 'fresh';
    echo '<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/pandastudio_framework/assets/js/vue.js"></script><script type="text/javascript" src="'.get_stylesheet_directory_uri().'/pandastudio_framework/assets/js/element-ui.js"></script><link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/pandastudio_framework/assets/css/'.$color.'.css"><link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/pandastudio_framework/assets/css/rewrite.css"><link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/pandastudio_framework/assets/css/font-awesome.css"><div id="vue_rest" class="wrap">';?><template><span v-if="false" style="color: red;">您的浏览器不支持ECMAScript 5，请更换至IE9及以上版本</span></template><template><el-tabs v-model="tabIndex" v-loading="loading" v-show="show"><el-tab-pane v-for="tab in tabs" v-if="gear_show(tab.gear_name,tab.gear_value)"><span slot="label"><i :class="tab.icon" class="fa"></i> {{tab.title}}</span><el-form ref="form" :label-width="tab.labelWidth" style="padding-right: 15px;" onsubmit="return false;"><el-form-item v-for="component in tab.content" v-if="gear_show(component.gear_name,component.gear_value)"><span slot="label" v-html="component.label"></span>
                    <div v-if=" component.type == 'input' "><el-input size="small" v-model="component.value" :placeholder="component.placeholder"></el-input></div>
                    <div v-if=" component.type == 'textarea' "><el-input size="small" type="textarea" :rows="component.rows" v-model="component.value" :placeholder="component.placeholder"></el-input></div>
                    <div v-if=" component.type == 'inputNumber' "><el-input-number size="small" v-model="component.value" :min="component.min" :max="component.max" :step="component.step"></el-input-number></div>
                    <div v-if=" component.type == 'slider' "><el-slider size="small" v-model="component.value" :min="component.min" :max="component.max" :step="component.step" show-input></el-slider></div>
                    <div v-if=" component.type == 'switch' "><el-switch size="small" v-model="component.value" active-value="checked" inactive-value=""></el-switch></div>
                    <div v-if=" component.type == 'colorPicker' "><el-color-picker size="small" v-model="component.value" :show-alpha="component.showAlpha"></el-color-picker></div>
                    <div v-if=" component.type == 'uploader' "><el-popover placement="bottom" trigger="hover" :disabled="component.value && !component.showImage"><el-input size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '点击按钮上传或在此处粘贴外链地址'" slot="reference"><el-button @click="mediaUpload(component.name,'','')" icon="el-icon-upload2" slot="prepend"></el-button><el-button @click="component.value = ''" icon="el-icon-close" slot="append" v-if="component.value"></el-button></el-input><img class="uploader_show_image" :src="component.value" background /></el-popover></div>
                    <div v-if=" component.type == 'multi_uploader' ">
                        <div class="multi_Uploader_show_image" v-for="(imgSrc , index) in component.value" :style="{ 'background-image': 'url(' + imgSrc + ')' }" @click="removeMultiUpload(component.name,index)"></div>
                        <div class="el-upload el-upload--picture-card">
                            <div class="hover"><el-tooltip class="item" effect="dark" content="输入外链" placement="top"><i class="el-icon-edit" @click="multiMediaUpload_input(component.name)"></i></el-tooltip><span style="width:15px;display: inline-block;"></span><el-tooltip class="item" effect="dark" content="批量上传" placement="top"><i class="el-icon-upload2" @click="multiMediaUpload(component.name)"></i></el-tooltip></div>
                            <div class="normal"><i class="el-icon-plus"></i></div>
                        </div>
                        <div v-if="component.value.length > 1"><el-button @click="removeAllMultiUpload(component.name)" type="danger" :plain="true" size="mini" icon="el-icon-delete" class="subcontentRemove">全部移除</el-button></div>
                    </div>
                    <div v-if=" component.type == 'radio' "><el-radio-group size="small" v-model="component.value"><el-radio class="radio" :label="radio.value" v-for="radio in component.radios"><span v-html="radio.label">{{radio.label}}</span></el-radio></el-radio-group></div>
                    <div v-if=" component.type == 'select' "><el-select size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '请选择'" clearable :multiple="component.multiple"><el-option v-for="select in component.selects" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">{{ select.description }}</span></el-option></el-select></div>
                    <div v-if=" component.type == 'categorySelect' "><el-select size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '请选择文章分类'" clearable><el-option v-for="select in categorySelect()" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                    <div v-if=" component.type == 'categoriesSelect' "><el-select size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '请选择至少一个文章分类'" multiple><el-option v-for="select in categorySelect()" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;padding-right: 20px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                    <div v-if=" component.type == 'singleSelect' "><el-select size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '输入关键字来筛选文章'" filterable remote clearable :remote-method="querySinglePosts" :loading="singleSelect.loading" @visible-change="queryExistValue($event,component.value)"><el-option v-for="select in singleSelect.query" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                    <div v-if=" component.type == 'multitypes' ">
                        <div v-if="component.displayAsTable == false"><el-form-item v-for="(subValue , index) in component.value" :label="component.subLabel + ' ' + (index+1)" :label-width="component.subLabelWidth">
                                <div v-for="subcomponent in component.types" style="margin-bottom: 5px;">
                                    <div style="color:#909399;">{{subcomponent.label ? subcomponent.label : ''}}</div>
                                    <div v-if="subcomponent.type == 'input'" class="subcomponent"><el-input size="small" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder"></el-input></div>
                                    <div v-if="subcomponent.type == 'textarea'" class="subcomponent"><el-input size="small" type="textarea" :rows="3" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder"></el-input></div>
                                    <div v-if="subcomponent.type == 'uploader'" class="subcomponent"><el-popover placement="bottom" trigger="hover" :disabled="subValue[subcomponent.name] && !subcomponent.showImage"><el-input size="small" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '点击按钮上传或在此处粘贴外链地址'" slot="reference"><el-button @click="mediaUpload(component.name,index,subcomponent.name)" icon="el-icon-upload2" slot="prepend"></el-button><el-button @click="subValue[subcomponent.name] = ''" icon="el-icon-close" slot="append" v-if="subValue[subcomponent.name]"></el-button></el-input><img class="uploader_show_image" :src="subValue[subcomponent.name]" background /></el-popover></div>
                                    <div v-if=" subcomponent.type == 'radio' " class="subcomponent"><el-radio-group size="small" v-model="subValue[subcomponent.name]"><el-radio class="radio" :label="radio.value" v-for="radio in subcomponent.radios">{{radio.label}}</el-radio></el-radio-group></div>
                                    <div v-if=" subcomponent.type == 'select' " class="subcomponent"><el-select size="small" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '请选择'" clearable :multiple="subcomponent.multiple"><el-option v-for="select in subcomponent.selects" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">{{ select.description }}</span></el-option></el-select></div>
                                    <div v-if=" subcomponent.type == 'categorySelect' " class="subcomponent"><el-select size="small" v-model="subValue[subcomponent.name]" placeholder="请选择文章分类" clearable><el-option v-for="select in categorySelect()" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                                    <div v-if=" subcomponent.type == 'categoriesSelect' " class="subcomponent"><el-select size="small" v-model="subValue[subcomponent.name]" placeholder="请选择至少一个文章分类" multiple><el-option v-for="select in categorySelect()" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;padding-right: 20px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                                    <div v-if=" subcomponent.type == 'singleSelect' "><el-select size="small" v-model="subValue[subcomponent.name]" :placeholder="component.placeholder ? component.placeholder : '输入关键字来筛选文章'" filterable remote clearable :remote-method="querySinglePosts" :loading="singleSelect.loading" @visible-change="queryExistValue($event,subValue[subcomponent.name])"><el-option v-for="select in singleSelect.query" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                                    <div v-if=" subcomponent.type == 'switch' "><el-switch size="small" v-model="subValue[subcomponent.name]" active-value="checked" inactive-value=""></el-switch></div>
                                </div><el-button :plain="true" size="small" icon="el-icon-arrow-up" @click="multitypeMoveUp(component.value,index)" :disabled="index == 0">上移</el-button><el-button :plain="true" size="small" icon="el-icon-arrow-down" @click="multitypeMoveDown(component.value,index)" :disabled="index == component.value.length - 1">下移</el-button><el-button @click="removeMultiTypes(component.name,index)" type="danger" :plain="true" size="small" icon="el-icon-delete" class="subcontentRemove">移除</el-button>
                            </el-form-item><el-form-item :label-width="component.value.length > 0 ? (component.subLabelWidth ? component.subLabelWidth : tabs[tabIndex].labelWidth) : '0px'"><el-button @click="addMultiTypes(component.name)" type="primary" size="mini" :plain="true" icon="el-icon-plus">{{component.subLabel}}</el-button></el-form-item></div>
                        <div v-if="component.displayAsTable == true"><el-table size="mini" :data="component.value" style="width: 100%" border><el-table-column type="index" width="55" fixed></el-table-column><el-table-column :prop="subcomponent.name" :label="subcomponent.label ? subcomponent.label : subcomponent.name" v-for="subcomponent in component.types" min-width="265"><template scope="scope">
                                        <div v-if="subcomponent.type == 'input'" class="subcomponent"><el-input v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder" size="small"></el-input></div>
                                        <div v-if="subcomponent.type == 'textarea'" class="subcomponent"><el-input type="textarea" :rows="3" v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder" size="small"></el-input></div>
                                        <div v-if="subcomponent.type == 'uploader'" class="subcomponent"><el-popover placement="bottom" trigger="hover" :disabled="scope.row[subcomponent.name] && !subcomponent.showImage"><el-input v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '点击按钮上传或在此处粘贴外链地址'" size="small" slot="reference"><el-button @click="mediaUpload(component.name,scope.$index,subcomponent.name)" icon="el-icon-upload2" slot="prepend"></el-button><el-button @click="scope.row[subcomponent.name] = ''" icon="el-icon-close" slot="append" v-if="scope.row[subcomponent.name]"></el-button></el-input><img class="uploader_show_image" :src="scope.row[subcomponent.name]" background /></el-popover></div>
                                        <div v-if=" subcomponent.type == 'radio' " class="subcomponent"><el-radio-group v-model="scope.row[subcomponent.name]" size="small"><el-radio class="radio" :label="radio.value" v-for="radio in subcomponent.radios">{{radio.label}}</el-radio></el-radio-group></div>
                                        <div v-if=" subcomponent.type == 'select' " class="subcomponent"><el-select v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '请选择'" size="small" clearable :multiple="subcomponent.multiple"><el-option v-for="select in subcomponent.selects" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">{{ select.description }}</span></el-option></el-select></div>
                                        <div v-if=" subcomponent.type == 'categorySelect' " class="subcomponent"><el-select v-model="scope.row[subcomponent.name]" placeholder="请选择文章分类" clearable size="small"><el-option v-for="select in categorySelect()" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                                        <div v-if=" subcomponent.type == 'categoriesSelect' " class="subcomponent"><el-select v-model="scope.row[subcomponent.name]" placeholder="请选择至少一个文章分类" multiple size="small"><el-option v-for="select in categorySelect()" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;padding-right: 20px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                                        <div v-if=" subcomponent.type == 'singleSelect' "><el-select v-model="scope.row[subcomponent.name]" :placeholder="component.placeholder ? component.placeholder : '输入关键字来筛选文章'" filterable remote clearable :remote-method="querySinglePosts" :loading="singleSelect.loading" @visible-change="queryExistValue($event,scope.row[subcomponent.name])" size="small"><el-option v-for="select in singleSelect.query" :key="select.value" :label="select.label" :value="select.value"><span style="float: left">{{ select.label }}</span><span style="float: right; color: #C0CCDA; font-size: 13px;margin-left:15px;">ID: {{ select.value }}</span></el-option></el-select></div>
                                        <div v-if=" subcomponent.type == 'switch' " size="small"><el-switch v-model="scope.row[subcomponent.name]" active-value="checked" inactive-value=""></el-switch></div>
                                    </template></el-table-column><el-table-column label="操作" width="175" fixed="right"><template scope="scope"><el-button :plain="true" size="mini" icon="el-icon-arrow-up" @click="multitypeMoveUp(component.value,scope.$index)" :disabled="scope.$index == 0"></el-button><el-button :plain="true" size="mini" icon="el-icon-arrow-down" @click="multitypeMoveDown(component.value,scope.$index)" :disabled="scope.$index == component.value.length - 1"></el-button><el-button type="danger" :plain="true" size="mini" icon="el-icon-delete" @click="component.value.splice(scope.$index,1)"></el-button></template></el-table-column></el-table><el-button @click="addMultiTypes(component.name)" type="primary" size="mini" :plain="true" icon="el-icon-plus">{{component.subLabel}}</el-button></div>
                    </div>
                    <div v-if=" component.type == 'uploadData' "><el-button size="small" @click="uploadData">上传配置</el-button></div>
                    <div v-if=" component.type == 'downloadData' "><el-button size="small" @click="downloadData">下载配置</el-button></div>
                    <div v-if=" component.type == 'clearData' "><el-button size="small" @click="clearData" icon="el-icon-delete" type="danger" :plain="true"></el-button></div>
                    <div class="form_decoration" v-html="component.decoration"></div>
                </el-form-item><el-form-item :label-width="tab.labelWidth"><el-button size="small" type="primary" icon="el-icon-check" @click="updateOption" v-if="showSaveBtn(tab.content)">保存全部</el-button></el-form-item></el-form></el-tab-pane></el-tabs></template></div>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/pandastudio_framework/assets/template/option_rest.js?version=<?php echo wp_get_theme()->get('Version') ?>"></script><?php
}?><?php
?>