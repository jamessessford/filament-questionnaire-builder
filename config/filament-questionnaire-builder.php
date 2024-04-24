<?php

return [
    'tables' => [
        'question-sets' => [
            'name' => 'question_sets',
        ],
        'questionnaires' => [
            'name' => 'questionnaires',
        ],
        'completed-questionnaires' => [
            'name' => 'completed_questionnaires',
        ],
    ],

    'models' => [
        'question-sets' => \PreferredManagement\FilamentQuestionnaireBuilder\Models\QuestionSet::class,
        'questionnaires' => \PreferredManagement\FilamentQuestionnaireBuilder\Models\Questionnaire::class,
        'completed-questionnaires' => \PreferredManagement\FilamentQuestionnaireBuilder\Models\CompletedQuestionnaire::class,
    ],

    'user-associations' => [
        'question-sets' => true,
        'questionnaires' => true,
    ],

    'tenant_model' => env('FQB_TENANT_MODEL', null),

    'user_model' => 'App\Models\User',
];
