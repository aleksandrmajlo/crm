<template>
    <div>
        <div class="row">
            <div class="col col-md-6">
                <dates-report></dates-report>
            </div>
            <div class="col col-md-6">
                <users-report></users-report>
            </div>
        </div>
        <dashbord-commentsadmin></dashbord-commentsadmin>
        <note-admin></note-admin>
    </div>
</template>
<script>
    import DatesReport from './DatesReport'
    import UsersReport from './UsersReport';
    import NoteAdmin from "../order/NoteAdmin";
    import DashbordCommentsadmin  from './DashbordCommentsadmin'
    export default {
        name: "DashbordAdmin",
        components: {
            DatesReport,
            UsersReport,
            NoteAdmin,
            DashbordCommentsadmin
        },
    };
</script>
