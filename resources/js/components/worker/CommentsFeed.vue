<template>
    <div class="commentsFeedWrap" v-if="comments">
        <div class="commentsFeedCont">
            <div class="tableFeedWrap" >
                <div class="mb-2">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr v-for="(comment,index) in comments" :key="index">
                                <td>{{comment.task_id}}</td>
                                <td class="nowrap">{{comment.created_at}}</td>
                                <td style="width:70%;"><span v-html="comment.commentadmin"></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="text-center mb-2" v-if="LoadBlock">
                    <button :disabled="disabled" class="btn btn-info" @click.prevent="LoadMore">Load more {{limit}}
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CommentsFeed",
        data() {
            return {
                comments: [],
                offset: 0,
                limit: 0,
                disabled: false,
                LoadBlock: true
            }
        },
        created() {
            this.getData();
        },
        methods: {
            getData() {
                this.disabled = true;
                axios
                    .get("/ajaxuser/commentsFeed", {params: {offset: this.offset}})
                    .then(response => {
                        if (response.data.success) {
                            if (response.data.comments.length > 0) {
                                this.comments = this.comments.concat(response.data.comments);
                                if (this.offset === 0) {
                                    this.offset = response.data.offset;
                                    this.limit = response.data.limit;
                                }
                                this.offset = this.offset + this.limit;
                            } else {
                                this.LoadBlock = false;
                            }

                        }
                    })
                    .catch(error => console.log(error))
                    .finally(() => {
                        this.disabled = false;
                    })
            },
            LoadMore() {
                this.getData();
            },
            ShowFeeds(){
                 $('.commentsFeedWrap').toggleClass('full')
            }
        }
    }
</script>


