<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $poll->status === 'draft' ? 'Editar Encuesta' : 'Ver Encuesta' }}
            </h2>
            <a href="{{ route('admin.polls.index') }}" class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="px-4 py-2 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="pollEditor(@js($poll))">
                    <form method="POST" action="{{ route('admin.polls.update', $poll) }}" @submit="prepareSubmit">
                        @csrf
                        @method('PUT')

                        <!-- Poll Details -->
                        <div class="p-4 mb-6 border border-gray-200 rounded-lg">
                            <h3 class="mb-4 text-lg font-semibold">Detalles de la Encuesta</h3>

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" x-model="poll.title" :disabled="poll.status === 'published'" required
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100">
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="description" id="description" rows="3" x-model="poll.description" :disabled="poll.status === 'published'"
                                          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100"></textarea>
                            </div>
                        </div>

                        <!-- Questions Section -->
                        <div class="p-4 mb-6 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">Preguntas (<span x-text="questions.length"></span>)</h3>
                                <button type="button" @click="addQuestion" x-show="poll.status === 'draft'"
                                        class="px-3 py-1 text-sm text-white bg-green-600 rounded-md hover:bg-green-700">
                                    + Agregar Pregunta
                                </button>
                            </div>

                            <!-- Questions List -->
                            <div class="space-y-4">
                                <template x-for="(question, qIndex) in questions" :key="qIndex">
                                    <div class="p-4 border border-gray-300 rounded-lg bg-gray-50">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-medium text-gray-700">Pregunta <span x-text="qIndex + 1"></span></h4>
                                            <button type="button" @click="removeQuestion(qIndex)" x-show="poll.status === 'draft'"
                                                    class="px-2 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </div>

                                        <input type="hidden" :name="`questions[${qIndex}][id]`" x-model="question.id">
                                        <input type="hidden" :name="`questions[${qIndex}][order]`" x-model="qIndex">

                                        <!-- Question Text -->
                                        <div class="mb-3">
                                            <label class="block mb-1 text-sm font-medium text-gray-700">Pregunta <span class="text-red-500">*</span></label>
                                            <input type="text" :name="`questions[${qIndex}][question]`" x-model="question.question" :disabled="poll.status === 'published'"
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100" required>
                                        </div>

                                        <!-- Question Type -->
                                        <div class="grid grid-cols-2 gap-4 mb-3">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium text-gray-700">Tipo <span class="text-red-500">*</span></label>
                                                <select :name="`questions[${qIndex}][type]`" x-model="question.type" :disabled="poll.status === 'published'"
                                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100">
                                                    <option value="yes_no">Sí/No</option>
                                                    <option value="multiple_choice">Opción Múltiple</option>
                                                    <option value="text">Texto</option>
                                                    <option value="number">Número</option>
                                                </select>
                                            </div>
                                            <div class="flex items-center">
                                                <label class="flex items-center">
                                                    <input type="checkbox" :name="`questions[${qIndex}][required]`" x-model="question.required" :disabled="poll.status === 'published'"
                                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:bg-gray-100" value="1">
                                                    <span class="ml-2 text-sm text-gray-700">Obligatoria</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Options for Multiple Choice -->
                                        <div x-show="question.type === 'multiple_choice'" class="p-3 border border-gray-200 rounded bg-white">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-sm font-medium text-gray-700">Opciones</label>
                                                <button type="button" @click="addOption(qIndex)" x-show="poll.status === 'draft'"
                                                        class="px-2 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    + Agregar Opción
                                                </button>
                                            </div>
                                            <div class="space-y-2">
                                                <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                                    <div class="flex gap-2">
                                                        <input type="hidden" :name="`questions[${qIndex}][options][${oIndex}][id]`" x-model="option.id">
                                                        <input type="hidden" :name="`questions[${qIndex}][options][${oIndex}][order]`" x-model="oIndex">
                                                        <input type="text" :name="`questions[${qIndex}][options][${oIndex}][option_text]`"
                                                               x-model="option.option_text" :disabled="poll.status === 'published'"
                                                               placeholder="Texto de la opción"
                                                               class="flex-1 text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100" required>
                                                        <button type="button" @click="removeOption(qIndex, oIndex)" x-show="poll.status === 'draft'"
                                                                class="px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600">
                                                            X
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="questions.length === 0" class="py-8 text-center text-gray-500">
                                    No hay preguntas. Haga clic en "Agregar Pregunta" para comenzar.
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3">
                            <button type="submit" x-show="poll.status === 'draft'" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Guardar Cambios
                            </button>

                            <button type="button" @click="publishPoll" x-show="poll.status === 'draft' && questions.length > 0"
                                    class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                                Publicar Encuesta
                            </button>

                            <a href="{{ route('admin.polls.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                {{ $poll->status === 'draft' ? 'Cancelar' : 'Cerrar' }}
                            </a>
                        </div>
                    </form>

                    <!-- Publish Form -->
                    <form x-ref="publishForm" method="POST" action="{{ route('admin.polls.publish', $poll) }}" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function pollEditor(initialPoll) {
            return {
                poll: initialPoll,
                questions: initialPoll.questions || [],

                addQuestion() {
                    this.questions.push({
                        id: null,
                        question: '',
                        type: 'yes_no',
                        required: true,
                        options: []
                    });
                },

                removeQuestion(index) {
                    if (confirm('¿Está seguro de eliminar esta pregunta?')) {
                        this.questions.splice(index, 1);
                    }
                },

                addOption(questionIndex) {
                    if (!this.questions[questionIndex].options) {
                        this.questions[questionIndex].options = [];
                    }
                    this.questions[questionIndex].options.push({
                        id: null,
                        option_text: ''
                    });
                },

                removeOption(questionIndex, optionIndex) {
                    this.questions[questionIndex].options.splice(optionIndex, 1);
                },

                prepareSubmit(event) {
                    // Form will submit naturally with all the data
                },

                publishPoll() {
                    if (confirm('¿Está seguro de publicar esta encuesta? Se enviarán emails a todos los miembros activos y no podrá editarla después.')) {
                        this.$refs.publishForm.submit();
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
