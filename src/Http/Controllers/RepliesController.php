<?php

namespace Achais\LaravelWechatReply\Http\Controllers;

use Achais\LaravelWechatReply\Exceptions\InternalException;
use Achais\LaravelWechatReply\Models\WeixinKeyword;
use Achais\LaravelWechatReply\Models\WeixinRule;
use Achais\LaravelWechatReply\Models\WeixinReply;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RepliesController extends Controller
{
    /**
     * 规则列表  目前展示所有  如果添加一个模糊  那么下面的查询就没必要了
     * @param Request $request
     * @return array
     * @throws InternalException
     */
    public function rules(Request $request)
    {
        $perPage = $request->get('perPage', 20);
        $query = WeixinRule::query();
        if ($name = $request->get('name')) {
            $query->where(function ($query) use ($name) {
                $query->where('rule_name', 'like', '%' . $name . '%')->orWhereHas('keywords', function ($query) use ($name) {
                    $query->where('keyword', $name);
                });
            });
        }
        $rules = $query->select('id', 'rule_name', 'reply_mode')->with([
            'keywords' => function ($query) {
                $query->select('keyword', 'full_match', 'weixin_rule_id');
            }
        ])->withCount('replies')->orderBy('id', 'desc')->paginate($perPage);

        $rulesArray = $rules->toArray();
        if (!empty($rulesArray['data'])) {
            foreach ($rulesArray['data'] as &$rule) {
                foreach ($rule['keywords'] as $key => $keyword) {
                    if ($key !== 0) {
                        $rule['keywords_string'] = $rule['keywords_string'] . ',' . $keyword['keyword'];
                    } else {
                        $rule['keywords_string'] = $keyword['keyword'];
                    }
                }
            }
        }
        return $this->success($rulesArray);
    }

    /**
     * 规则详情
     * @param Request $request
     * @return array
     * @throws InternalException
     */
    public function rulesShow(Request $request)
    {
        $id = $request->input('id');
        $ruleOne = WeixinRule::query()->where('id', $id)->select('id', 'rule_name', 'reply_mode')->with([
            'keywords' => function ($query) {
                $query->select('id', 'keyword', 'full_match', 'weixin_rule_id');
            },
            'replies' => function ($query) {
                $query->select('id', 'type', 'content', 'weixin_rule_id');
            }
        ])->first();
        if (is_null($ruleOne)) {
            return $this->fail('规则不存在');
        }
        return $this->success($ruleOne->toArray());
    }

    /**
     * 创建规则
     * @param Request $request
     * @return array
     * @throws InternalException
     */
    public function rulesCreate(Request $request)
    {
        $this->validate($request, [
            'rule_name' => 'required|max:60',
            'reply_mode' => ['required', Rule::in(WeixinRule::REPLY_MODE_RANDOM, WeixinRule::REPLY_MODE_ALL)],
            'replies' => 'array',
            'replies.*.type' => ['required', Rule::in(WeixinReply::TYPE_TEXT, WeixinReply::TYPE_IMAGE, WeixinReply::TYPE_VOICE, WeixinReply::TYPE_VIDEO, WeixinReply::TYPE_MUSIC, WeixinReply::TYPE_NEWS)],
            'replies.*.content' => 'required|max:300',
            'keywords' => 'array',
            'keywords.*.keyword' => 'required|max:30',
            'keywords.*.full_match' => 'boolean',
        ]);

        $data = $request->all();

        $keywords = $request->input('keywords', []);
        if (empty($keywords)) {
            return $this->fail('关键词不能为空');
        } else if (count($keywords) > 10) {
            return $this->fail('关键词最多10条');
        }

        $replies = $request->input('replies', []);
        if (empty($replies)) {
            return $this->fail('回复内容不能为空');
        } else if (count($replies) > 5) {
            return $this->fail('回复内容最多5条');
        }

        return DB::transaction(function () use ($request) {
            $weiXinRule = new WeixinRule();
            $weiXinRule->rule_name = $request->input('rule_name');
            $weiXinRule->reply_mode = $request->input('reply_mode');
            if (!$weiXinRule->save()) {
                DB::rollBack();
                return $this->fail('规则保存失败');
            }

            $keywords = $request->input('keywords', []);
            foreach ($keywords as $keyword) {
                $weiXinKeyword = new WeixinKeyword();
                $weiXinKeyword->weixin_rule_id = $weiXinRule->id;
                $weiXinKeyword->keyword = $keyword['keyword'];
                $weiXinKeyword->full_match = $keyword['full_match'];
                if (!$weiXinKeyword->save()) {
                    DB::rollBack();
                    return $this->fail('规则的关键词保存失败');
                }
            }

            $replies = $request->input('replies', []);
            foreach ($replies as $reply) {
                $weiXinReply = new WeixinReply();
                $weiXinReply->weixin_rule_id = $weiXinRule->id;
                $weiXinReply->content = $reply['content'];
                $weiXinReply->type = $reply['type'];
                if (!$weiXinReply->save()) {
                    DB::rollBack();
                    return $this->fail('规则的回复内容保存失败');
                }
            }
            return $this->success('成功');
        });
    }

    /**
     * 编辑规则
     * @param Request $request
     * @return array
     * @throws InternalException
     */
    public function rulesUpdate(Request $request)
    {

        $this->validate($request, [
            'id' => 'required|exists:weixin_rules,id',
            'rule_name' => 'required|max:60',
            'reply_mode' => ['required', Rule::in(WeixinRule::REPLY_MODE_RANDOM, WeixinRule::REPLY_MODE_ALL)],
            'replies' => 'array',
            'replies.*.id' => 'sometimes|required|exists:weixin_replies,id',
            'replies.*.type' => ['required', Rule::in(WeixinReply::TYPE_TEXT, WeixinReply::TYPE_IMAGE, WeixinReply::TYPE_VOICE, WeixinReply::TYPE_VIDEO, WeixinReply::TYPE_MUSIC, WeixinReply::TYPE_NEWS)],
            'replies.*.content' => 'required|max:300',
            'keywords' => 'array',
            'keywords.*.id' => 'sometimes|required|exists:weixin_keywords,id',
            'keywords.*.keyword' => 'required|max:30',
            'keywords.*.full_match' => 'required|boolean',
        ]);

        $keywords = $request->input('keywords');
        if (count($keywords) == 0) {
            return $this->fail('关键词不能为空');
        } else if (count($keywords) > 10) {
            return $this->fail('关键词最多10条');
        }
        $replies = $request->input('replies');
        if (count($replies) == 0) {
            return $this->fail('回复内容不能为空');
        } else if (count($replies) > 5) {
            return $this->fail('回复内容最多5条');
        }
        $weiXinRule = WeixinRule::query()->where('id', $request->input('id'))->first();
        if (!$weiXinRule) {
            return $this->fail('规则不存在');
        }
        DB::beginTransaction();
        $weiXinRule->rule_name = $request->input('rule_name');
        $weiXinRule->reply_mode = $request->input('reply_mode');
        if (!$weiXinRule->save()) {
            DB::rollBack();
            return $this->fail('规则保存失败');
        }
        $weiXinKeywords_ids = $weiXinRule->keywords()->select('id')->get();
        $weiXinKeywords_ids = is_null($weiXinKeywords_ids) ? [] : $weiXinKeywords_ids->toArray();
        $keywords_ids = [];
        foreach ($weiXinKeywords_ids as $weiXinKeywords_id) {
            if (isset($weiXinKeywords_id['id'])) {
                $keywords_ids[] = $weiXinKeywords_id['id'];
            }
        }

        foreach ($keywords as $keyword) {
            if (!isset($keyword['id'])) {
                $weiXinKeyword = new WeixinKeyword();
                $weiXinKeyword->weixin_rule_id = $weiXinRule->id;
            } else {
                $weiXinKeyword = $weiXinRule->keywords()->where('id', $keyword['id'])->first();
                if (is_null($weiXinKeyword)) {
                    DB::rollBack();
                    return $this->fail('信息有误，请刷新后重试');
                }
                $index = array_search($keyword['id'], $keywords_ids);
                if ($index !== false) array_splice($keywords_ids, $index, 1);
            }
            $weiXinKeyword->keyword = $keyword['keyword'];
            $weiXinKeyword->full_match = $keyword['full_match'];
            if (!$weiXinKeyword->save()) {
                DB::rollBack();
                return $this->fail('规则的关键词保存失败');
            }
        }
        if (!empty($keywords_ids)) {
            WeixinKeyword::query()->whereIn('id', $keywords_ids)->delete();
        }

        $weiXinReplies_ids = $weiXinRule->replies()->select('id')->get();
        $weiXinReplies_ids = is_null($weiXinReplies_ids) ? [] : $weiXinReplies_ids->toArray();
        $replies_ids = [];
        foreach ($weiXinReplies_ids as $weiXinReplies_id) {
            if (isset($weiXinReplies_id['id'])) {
                $replies_ids[] = $weiXinReplies_id['id'];
            }
        }
        foreach ($replies as $reply) {
            if (!isset($reply['id'])) {
                $weiXinReply = new WeixinReply();
                $weiXinReply->weixin_rule_id = $weiXinRule->id;
            } else {
                $weiXinReply = $weiXinRule->replies()->where('id', $reply['id'])->first();
                if (is_null($weiXinReply)) {
                    DB::rollBack();
                    return $this->fail('信息有误，请刷新后重试');
                }
                $index = array_search($reply['id'], $replies_ids);
                if ($index !== false) array_splice($replies_ids, $index, 1);
            }
            $weiXinReply->content = $reply['content'];
            $weiXinReply->type = $reply['type'];
            if (!$weiXinReply->save()) {
                DB::rollBack();
                return $this->fail('规则的回复内容保存失败');
            }
        }

        if (!empty($replies_ids)) {
            WeixinReply::query()->whereIn('id', $replies_ids)->delete();
        }

        DB::commit();
        return $this->success('成功');
    }

    /**
     * 删除
     * @param Request $request
     * @return array
     * @throws InternalException
     */
    public function rulesDestroy(Request $request)
    {
        $ruleOne = WeixinRule::query()->where('id', $request->input('id'))->first();
        if (!$ruleOne) {
            return $this->success('删除成功');
        }
        $ruleOne->keywords()->delete();
        $ruleOne->replies()->delete();
        $ruleOne->delete();
        return $this->success('删除成功');
    }
}