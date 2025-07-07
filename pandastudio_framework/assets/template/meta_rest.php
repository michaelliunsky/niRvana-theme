<?php
add_action('rest_api_init', function () {
    register_rest_route('pandastudio/framework', '/get_post_meta/', array(
        'methods' => 'POST',
        'callback' => 'get_post_meta_by_RestAPI',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('pandastudio/framework', '/update_post_meta/', array(
        'methods' => 'POST',
        'callback' => 'update_post_meta_by_RestAPI',
        'permission_callback' => function () {
            return current_user_can('publish_posts');
        },
    ));
});

function get_post_meta_by_RestAPI($data)
{
    $ajaxData = json_decode($data->get_body(), true);
    $post_id = $ajaxData['postID'] ?? 0;
    $dataArray = $ajaxData['postMeta'] ?? array();
    if (!$post_id) {
        return array('error' => '缺少PostID！');
    }
    if (count($dataArray) < 1) {
        return array('error' => '数据格式不正确或为空！');
    }
    $return = array();
    foreach ($dataArray as $meta_name => $value) {
        $meta_val = get_post_meta($post_id, $meta_name, true);
        $return[$meta_name] = $meta_val !== '' ? $meta_val : "";
    }
    return $return;
}

function update_post_meta_by_RestAPI($data)
{
    if (!current_user_can('publish_posts')) {
        return array('state'=>false,'error'=>'授权错误！');
    }
    $ajaxData = json_decode($data->get_body(), true);
    $post_id = $ajaxData['postID'] ?? 0;
    $dataArray = $ajaxData['postMeta'] ?? array();
    if (!$post_id) {
        return array('state'=>false,'error' => '缺少PostID！');
    }
    foreach ($dataArray as $meta_name => $value) {
        update_post_meta($post_id, $meta_name, $value);
    }
    return array('state'=>true);
}

add_action('admin_menu', 'pandastudio_framework_create_json_meta');
function pandastudio_framework_create_json_meta()
{
    global $meta_screens;
    $wp_all_post_types = get_post_types();
    foreach ($wp_all_post_types as $post_type) {
        if (isset($meta_screens) && in_array($post_type, $meta_screens)) {
            $post_type_obj = get_post_type_object($post_type);
            $post_type_name = $post_type_obj->labels->name;
            add_meta_box('pandastudio_framework_meta', $post_type_name.'设置', 'pandastudio_framework_create_json_meta_box', $post_type, 'advanced', 'high', '');
        }
    }
}

function pandastudio_framework_create_json_meta_box()
{
    wp_enqueue_media();
    $adminColor = get_user_meta(get_current_user_id(), 'admin_color', true);
    $supportColorArray = array('blue','coffee','ectoplasm','fresh','light','midnight','ocean','sunrise');
    $color = in_array($adminColor, $supportColorArray) ? $adminColor : 'fresh';

    echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/pandastudio_framework/assets/js/vue.js"></script>';
    echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/pandastudio_framework/assets/js/element-ui.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/pandastudio_framework/assets/css/' . $color . '.css">';
    echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/pandastudio_framework/assets/css/rewrite_post_screen.css">';
    echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/pandastudio_framework/assets/css/font-awesome.css">';
    echo '<div id="vue_rest">';
    ?>
    <template>
        <span v-if="false" style="color: red;">您的浏览器不支持ECMAScript 5，请更换至IE9及以上版本</span>
    </template>
    <template>
        <el-tabs v-model="tabIndex" v-loading="loading" v-show="show" class="panda_framework_metabox_tab" type="card">
            <el-tab-pane v-for="tab in tabs" v-if="gear_show(tab.gear_name,tab.gear_value)">
                <span slot="label"><i :class="tab.icon" class="fa"></i> {{tab.title}}</span>
                <el-form ref="form" :label-width="tab.labelWidth" style="padding-right: 15px;" onsubmit="return false;">
                    <el-form-item v-for="component in tab.content" v-if="gear_show(component.gear_name,component.gear_value)">
                        <span slot="label" v-html="component.label"></span>
                        <div v-if="component.type == 'input'">
                            <el-input size="small" v-model="component.value" :placeholder="component.placeholder"></el-input>
                        </div>
                        <div v-if="component.type == 'textarea'">
                            <el-input size="small" type="textarea" :rows="component.rows" v-model="component.value" :placeholder="component.placeholder"></el-input>
                        </div>
                        <div v-if="component.type == 'inputNumber'">
                            <el-input-number size="small" v-model="component.value" :min="component.min" :max="component.max" :step="component.step"></el-input-number>
                        </div>
                        <div v-if="component.type == 'slider'">
                            <el-slider size="small" v-model="component.value" :min="component.min" :max="component.max" :step="component.step" show-input></el-slider>
                        </div>
                        <div v-if="component.type == 'switch'">
                            <el-switch size="small" v-model="component.value" active-value="checked" inactive-value=""></el-switch>
                        </div>
                        <div v-if="component.type == 'colorPicker'">
                            <el-color-picker size="small" v-model="component.value" :show-alpha="component.showAlpha"></el-color-picker>
                        </div>
                        <div v-if="component.type == 'uploader'">
                            <el-popover placement="bottom" trigger="hover" :disabled="component.value && !component.showImage">
                                <el-input size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '点击按钮上传或在此处粘贴外链地址'" slot="reference">
                                    <el-button @click="mediaUpload(component.name,'','')" icon="el-icon-upload2" slot="prepend"></el-button>
                                    <el-button @click="component.value = ''" icon="el-icon-close" slot="append" v-if="component.value"></el-button>
                                </el-input>
                                <img class="uploader_show_image" :src="component.value" background />
                            </el-popover>
                        </div>
                        <div v-if="component.type == 'multi_uploader'">
                            <div class="multi_Uploader_show_image" v-for="(imgSrc, index) in component.value" :style="{ 'background-image': 'url(' + imgSrc + ')' }" @click="removeMultiUpload(component.name,index)"></div>
                            <div class="el-upload el-upload--picture-card">
                                <div class="hover">
                                    <el-tooltip class="item" effect="dark" content="输入外链" placement="top">
                                        <i class="el-icon-edit" @click="multiMediaUpload_input(component.name)"></i>
                                    </el-tooltip>
                                    <span style="width:15px;display: inline-block;"></span>
                                    <el-tooltip class="item" effect="dark" content="批量上传" placement="top">
                                        <i class="el-icon-upload2" @click="multiMediaUpload(component.name)"></i>
                                    </el-tooltip>
                                </div>
                                <div class="normal"><i class="el-icon-plus"></i></div>
                            </div>
                            <div v-if="component.value.length > 1">
                                <el-button @click="removeAllMultiUpload(component.name)" type="danger" :plain="true" size="mini" icon="el-icon-delete" class="subcontentRemove">全部移除</el-button>
                            </div>
                        </div>
                        <div v-if="component.type == 'radio'">
                            <el-radio-group size="small" v-model="component.value">
                                <el-radio class="radio" :label="radio.value" v-for="radio in component.radios">
                                    <span v-html="radio.label">{{radio.label}}</span>
                                </el-radio>
                            </el-radio-group>
                        </div>
                        <div v-if="component.type == 'select'">
                            <el-select size="small" v-model="component.value" :placeholder="component.placeholder ? component.placeholder : '请选择'" clearable>
                                <el-option v-for="select in component.selects" :key="select.value" :label="select.label" :value="select.value">
                                    <span style="float: left">{{ select.label }}</span>
                                    <span style="float: right; color: #C0CCDA; font-size: 13px">{{ select.description }}</span>
                                </el-option>
                            </el-select>
                        </div>
                        <div v-if="component.type == 'multitypes'">
                            <div v-if="component.displayAsTable == false">
                                <el-form-item v-for="(subValue, index) in component.value" :label="component.subLabel + ' ' + (index+1)" :label-width="component.subLabelWidth">
                                    <div v-for="subcomponent in component.types" style="margin-bottom: 5px;">
                                        <div v-if="subcomponent.type == 'input'" class="subcomponent">
                                            <el-input size="small" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder"></el-input>
                                        </div>
                                        <div v-if="subcomponent.type == 'textarea'" class="subcomponent">
                                            <el-input size="small" type="textarea" :rows="3" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder"></el-input>
                                        </div>
                                        <div v-if="subcomponent.type == 'uploader'" class="subcomponent">
                                            <el-popover placement="bottom" trigger="hover" :disabled="subValue[subcomponent.name] && !subcomponent.showImage">
                                                <el-input size="small" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '点击按钮上传或在此处粘贴外链地址'" slot="reference">
                                                    <el-button @click="mediaUpload(component.name,index,subcomponent.name)" icon="el-icon-upload2" slot="prepend"></el-button>
                                                    <el-button @click="subValue[subcomponent.name] = ''" icon="el-icon-close" slot="append" v-if="subValue[subcomponent.name]"></el-button>
                                                </el-input>
                                                <img class="uploader_show_image" :src="subValue[subcomponent.name]" background />
                                            </el-popover>
                                        </div>
                                        <div v-if="subcomponent.type == 'radio'" class="subcomponent">
                                            <el-radio-group size="small" v-model="subValue[subcomponent.name]">
                                                <el-radio class="radio" :label="radio.value" v-for="radio in subcomponent.radios">{{radio.label}}</el-radio>
                                            </el-radio-group>
                                        </div>
                                        <div v-if="subcomponent.type == 'select'" class="subcomponent">
                                            <el-select size="small" v-model="subValue[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '请选择'" clearable>
                                                <el-option v-for="select in subcomponent.selects" :key="select.value" :label="select.label" :value="select.value">
                                                    <span style="float: left">{{ select.label }}</span>
                                                    <span style="float: right; color: #C0CCDA; font-size: 13px">{{ select.description }}</span>
                                                </el-option>
                                            </el-select>
                                        </div>
                                        <div v-if="subcomponent.type == 'switch'">
                                            <el-switch size="small" v-model="subValue[subcomponent.name]" active-value="checked" inactive-value=""></el-switch>
                                        </div>
                                    </div>
                                    <el-button :plain="true" size="small" icon="el-icon-arrow-up" @click="multitypeMoveUp(component.value,index)" :disabled="index == 0">上移</el-button>
                                    <el-button :plain="true" size="small" icon="el-icon-arrow-down" @click="multitypeMoveDown(component.value,index)" :disabled="index == component.value.length - 1">下移</el-button>
                                    <el-button @click="removeMultiTypes(component.name,index)" type="danger" :plain="true" size="small" icon="el-icon-delete" class="subcontentRemove">移除</el-button>
                                </el-form-item>
                                <el-form-item :label-width="component.value.length > 0 ? (component.subLabelWidth ? component.subLabelWidth : tabs[tabIndex].labelWidth) : '0px'">
                                    <el-button @click="addMultiTypes(component.name)" type="primary" size="mini" :plain="true" icon="el-icon-plus">{{component.subLabel}}</el-button>
                                </el-form-item>
                            </div>
                            <div v-if="component.displayAsTable == true">
                                <el-table size="mini" :data="component.value" style="width: 100%" border>
                                    <el-table-column type="index" width="55" fixed></el-table-column>
                                    <el-table-column :prop="subcomponent.name" :label="subcomponent.label ? subcomponent.label : subcomponent.name" v-for="subcomponent in component.types" min-width="265">
                                        <template scope="scope">
                                            <div v-if="subcomponent.type == 'input'" class="subcomponent">
                                                <el-input v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder" size="small"></el-input>
                                            </div>
                                            <div v-if="subcomponent.type == 'textarea'" class="subcomponent">
                                                <el-input type="textarea" :rows="3" v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder" size="small"></el-input>
                                            </div>
                                            <div v-if="subcomponent.type == 'uploader'" class="subcomponent">
                                                <el-popover placement="bottom" trigger="hover" :disabled="scope.row[subcomponent.name] && !subcomponent.showImage">
                                                    <el-input v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '点击按钮上传或在此处粘贴外链地址'" size="small" slot="reference">
                                                        <el-button @click="mediaUpload(component.name,scope.$index,subcomponent.name)" icon="el-icon-upload2" slot="prepend"></el-button>
                                                        <el-button @click="scope.row[subcomponent.name] = ''" icon="el-icon-close" slot="append" v-if="scope.row[subcomponent.name]"></el-button>
                                                    </el-input>
                                                    <img class="uploader_show_image" :src="scope.row[subcomponent.name]" background />
                                                </el-popover>
                                            </div>
                                            <div v-if="subcomponent.type == 'radio'" class="subcomponent">
                                                <el-radio-group v-model="scope.row[subcomponent.name]" size="small">
                                                    <el-radio class="radio" :label="radio.value" v-for="radio in subcomponent.radios">{{radio.label}}</el-radio>
                                                </el-radio-group>
                                            </div>
                                            <div v-if="subcomponent.type == 'select'" class="subcomponent">
                                                <el-select v-model="scope.row[subcomponent.name]" :placeholder="subcomponent.placeholder ? subcomponent.placeholder : '请选择'" size="small" clearable>
                                                    <el-option v-for="select in subcomponent.selects" :key="select.value" :label="select.label" :value="select.value">
                                                        <span style="float: left">{{ select.label }}</span>
                                                        <span style="float: right; color: #C0CCDA; font-size: 13px">{{ select.description }}</span>
                                                    </el-option>
                                                </el-select>
                                            </div>
                                            <div v-if="subcomponent.type == 'switch'" size="small">
                                                <el-switch v-model="scope.row[subcomponent.name]" active-value="checked" inactive-value=""></el-switch>
                                            </div>
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="操作" width="175" fixed="right">
                                        <template scope="scope">
                                            <el-button :plain="true" size="mini" icon="el-icon-arrow-up" @click="multitypeMoveUp(component.value,scope.$index)" :disabled="scope.$index == 0"></el-button>
                                            <el-button :plain="true" size="mini" icon="el-icon-arrow-down" @click="multitypeMoveDown(component.value,scope.$index)" :disabled="scope.$index == component.value.length - 1"></el-button>
                                            <el-button type="danger" :plain="true" size="mini" icon="el-icon-delete" @click="component.value.splice(scope.$index,1)"></el-button>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <el-button @click="addMultiTypes(component.name)" type="primary" size="mini" :plain="true" icon="el-icon-plus">{{component.subLabel}}</el-button>
                            </div>
                        </div>
                        <div v-if="component.type == 'view'" class="pandastudio_view">
                            <component :is="component.template_name"></component>
                        </div>
                        <div class="form_decoration" v-html="component.decoration"></div>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </template>
    </div>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/pandastudio_framework/assets/template/meta_rest.js?version=<?php echo wp_get_theme()->get('Version'); ?>"></script>
    <?php
}?><?php
?>