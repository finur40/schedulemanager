<!DOCTYPE html>
<html lang="en" x-data="courseApp()">
<head>
    <meta charset="UTF-8" />
    <title>Schedule Manager</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
</head>
<body class="bg-base-200 text-base-content">
    <div class="flex min-h-screen primary">

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Schedule Manager</h1>
                <div class="flex items-center gap-2">
                    <select class="select" x-model="selectedSemesterId">
                        <template x-for="semester in semesters" :key="semester.id">
                            <option :value="semester.id" x-text="semester.name"></option>
                        </template>
                    </select>
                    <button @click="showAdd = true" class="btn btn-primary">+ Add Course</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-box">
                <table class="table table-fixed">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Days</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Professor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="course in courses" :key="course.id">
                            <tr>
                                <td x-text="course.name"></td>
                                <td x-text="course.day"></td>
                                <td x-text="course.time_start + ' – ' + course.time_end"></td>
                                <td x-text="course.room"></td>
                                <td x-text="course.professor"></td>
                                <td class="flex gap-2">
                                    <button @click="editCourse(course)" class="btn btn-sm btn-ghost">✏️</button>
                                    <button @click="deleteCourse(course.id)" class="btn btn-sm btn-ghost text-error">🗑️</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Add Course Modal -->
            <template x-teleport="body">
            <div x-show="showAdd" x-transition class="modal modal-open">
                <div class="modal-box">
                    <h3 class="font-bold text-lg mb-4">Add Course</h3>
                    <form @submit.prevent="submitAdd" class="flex flex-col items-center">
                        <input x-model="form.name" name="name" placeholder="Course name" class="input input-bordered ite w-full mb-2" required />
                        <input x-model="form.day" name="day" placeholder="Day (e.g. Mon)" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.time_start" name="time_start" placeholder="Start Time" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.time_end" name="time_end" placeholder="End Time" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.room" name="room" placeholder="Room" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.professor" name="professor" placeholder="Professor" class="input input-bordered w-full mb-2" required />
                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn" @click="showAdd = false">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
</template>
            <!-- Edit Course Modal -->
            <div x-show="showEdit" x-transition class="modal modal-open">
                <div class="modal-box">
                    <h3 class="font-bold text-lg mb-4">Edit Course</h3>
                    <form @submit.prevent="submitEdit" class="flex flex-col items-center">
                        <input x-model="form.name" placeholder="Course name" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.day" placeholder="Day" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.time_start" placeholder="Start Time" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.time_end" placeholder="End Time" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.room" placeholder="Room" class="input input-bordered w-full mb-2" required />
                        <input x-model="form.professor" placeholder="Professor" class="input input-bordered w-full mb-2" required />
                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn" @click="showEdit = false">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function courseApp() {
            return {
                courses: [],
                semesters: [],
                selectedSemesterId: null,
                showAdd: false,
                showEdit: false,
                editingId: null,
                form: {
                    name: '',
                    day: '',
                    time_start: '',
                    time_end: '',
                    room: '',
                    professor: '',
                    semester_id: null
                },

                init() {
                    this.loadSemesters();
                    this.$watch('selectedSemesterId', () => this.loadCourses());
                },

                loadSemesters() {
                    fetch('/semesters')
                        .then(res => res.json())
                        .then(data => {
                            this.semesters = data;
                            if (data.length) {
                                this.selectedSemesterId = data[0].id;
                            }
                        });
                },

                loadCourses() {
                    if (!this.selectedSemesterId) return;
                    fetch(`/schedule?semester_id=${this.selectedSemesterId}`)
                        .then(res => res.json())
                        .then(data => this.courses = data);
                },

                resetForm() {
                    this.form = {
                        name: '',
                        day: '',
                        time_start: '',
                        time_end: '',
                        room: '',
                        professor: '',
                        semester_id: null
                    };
                },

                submitAdd() {
                    this.form.semester_id = this.selectedSemesterId;
                    fetch('/schedule', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    }).then(() => {
                        this.showAdd = false;
                        this.resetForm();
                        this.loadCourses();
                    });
                },

                editCourse(course) {
                    this.editingId = course.id;
                    this.form = { ...course };
                    this.showEdit = true;
                },

                submitEdit() {
                    this.form.semester_id = this.selectedSemesterId;
                    fetch(`/schedule/${this.editingId}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    }).then(() => {
                        this.showEdit = false;
                        this.resetForm();
                        this.loadCourses();
                    });
                },

                deleteCourse(id) {
                    if (!confirm('Are you sure you want to delete this course?')) return;
                    fetch(`/schedule/${id}`, { method: 'DELETE' })
                        .then(() => this.loadCourses());
                }
            };
        }
    </script>
</body>
</html>