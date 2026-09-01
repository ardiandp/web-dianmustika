<?php

/**
 * Konfigurasi alur form konsultasi Dian Mustika.
 *
 * Struktur tiap langkah (step):
 *   - key      : identifier langkah (dipakai untuk session & review)
 *   - title    : judul langkah
 *   - fields   : daftar field / question
 *
 * Struktur tiap field:
 *   - key      : identifier jawaban
 *   - label    : pertanyaan
 *   - description : deskripsi singkat (opsional)
 *   - type     : text | tel | number | textarea | radio | checkbox | select
 *   - options  : untuk radio/select => [ ['value' => ..., 'label' => ...], ... ]
 *                untuk checkbox => daftar value (label = humanize)
 *   - options_label : map value => label (opsional untuk checkbox)
 *   - required : bool
 *   - placeholder : string
 *   - unit     : satuan (untuk number)
 *   - condition: array { field, operator, value } — tampilkan hanya bila terpenuhi
 *   - reveal   : jika true, gunakan logika pertanyaan lanjutan (lihat di bawah)
 *   - others_textarea : tampilkan textarea "Lainnya" bila opsi tertentu dipilih
 *   - children : daftar sub-field yang tampil bila opsi selected_value dipilih
 */

return [

    'flow_name' => 'homecare',
    'flow_label' => 'Konsultasi Homecare Pascamelahirkan',

    'steps' => [
        [
            'key' => 'data_ibu',
            'title' => 'Data Ibu',
            'fields' => [
                ['key' => 'name', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama lengkap Anda'],
                ['key' => 'phone', 'label' => 'Nomor WhatsApp', 'type' => 'tel', 'required' => true, 'placeholder' => '08xxxxxxxxxx'],
                ['key' => 'instagram', 'label' => 'Instagram', 'type' => 'text', 'required' => false, 'placeholder' => '@username'],
                ['key' => 'no_instagram', 'label' => 'Saya tidak memiliki Instagram', 'type' => 'checkbox_single', 'required' => false],
                ['key' => 'address', 'label' => 'Alamat Homecare', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Alamat lengkap untuk homecare'],
                ['key' => 'height_cm', 'label' => 'Tinggi Badan', 'type' => 'number', 'required' => false, 'unit' => 'cm'],
                ['key' => 'weight_kg', 'label' => 'Berat Badan', 'type' => 'number', 'required' => false, 'unit' => 'kg'],
                ['key' => 'birth_count', 'label' => 'Kelahiran ke-', 'type' => 'select', 'required' => false, 'options' => [
                    ['value' => 'pertama', 'label' => 'Pertama'],
                    ['value' => 'kedua', 'label' => 'Kedua'],
                    ['value' => 'ketiga', 'label' => 'Ketiga'],
                    ['value' => 'keempat', 'label' => 'Keempat'],
                    ['value' => 'lebih_dari_4', 'label' => 'Lebih dari 4'],
                ]],
                ['key' => 'follow_ig', 'label' => 'Sudah follow Instagram Dian Mustika?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'sudah', 'label' => 'Sudah'],
                    ['value' => 'belum', 'label' => 'Belum'],
                ]],
            ],
        ],

        [
            'key' => 'kondisi_pascamelahirkan',
            'title' => 'Kondisi Pascamelahiran',
            'fields' => [
                ['key' => 'postpartum_period', 'label' => 'Sudah berapa lama setelah melahirkan?', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => '0-7_hari', 'label' => '0–7 hari'],
                    ['value' => '8-14_hari', 'label' => '8–14 hari'],
                    ['value' => '15-30_hari', 'label' => '15–30 hari'],
                    ['value' => '1-3_bulan', 'label' => '1–3 bulan'],
                    ['value' => '3-6_bulan', 'label' => '3–6 bulan'],
                    ['value' => 'lebih_dari_6_bulan', 'label' => 'Lebih dari 6 bulan'],
                ]],
                ['key' => 'delivery_type', 'label' => 'Jenis persalinan', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'normal', 'label' => 'Normal'],
                    ['value' => 'caesar', 'label' => 'Caesar'],
                    ['value' => 'lainnya', 'label' => 'Lainnya'],
                ]],
                // Conditional normal
                ['key' => 'perineal_stitches', 'label' => 'Apakah terdapat jahitan pada area perineum/vagina?', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'tidak', 'label' => 'Tidak'],
                    ['value' => 'ada', 'label' => 'Ada'],
                    ['value' => 'tidak_yakin', 'label' => 'Tidak yakin'],
                ], 'condition' => ['field' => 'delivery_type', 'operator' => 'equals', 'value' => 'normal']],
                ['key' => 'stitch_healing', 'label' => 'Apakah area jahitan masih dalam proses penyembuhan?', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'ya', 'label' => 'Ya'],
                    ['value' => 'tidak', 'label' => 'Tidak'],
                    ['value' => 'tidak_yakin', 'label' => 'Tidak yakin'],
                ], 'condition' => ['field' => 'perineal_stitches', 'operator' => 'equals', 'value' => 'ada']],
                // Conditional caesar
                ['key' => 'c_section_wound', 'label' => 'Kondisi luka operasi saat ini', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'baik', 'label' => 'Sudah baik'],
                    ['value' => 'penyembuhan', 'label' => 'Masih dalam proses penyembuhan'],
                    ['value' => 'keluhan', 'label' => 'Masih ada keluhan'],
                    ['value' => 'tidak_yakin', 'label' => 'Tidak yakin'],
                ], 'condition' => ['field' => 'delivery_type', 'operator' => 'equals', 'value' => 'caesar']],
            ],
        ],

        [
            'key' => 'tujuan_perawatan',
            'title' => 'Tujuan Perawatan',
            'fields' => [
                ['key' => 'treatment_goals', 'label' => 'Apa tujuan utama Anda melakukan perawatan?', 'type' => 'checkbox', 'required' => true, 'options' => [
                    ['value' => 'relaksasi', 'label' => 'Relaksasi'],
                    ['value' => 'urangi_pegal', 'label' => 'Mengurangi rasa pegal'],
                    ['value' => 'bugar', 'label' => 'Membantu tubuh terasa lebih bugar'],
                    ['value' => 'perawatan_tubuh', 'label' => 'Perawatan tubuh setelah melahirkan'],
                    ['value' => 'fokus_perut', 'label' => 'Fokus area perut'],
                    ['value' => 'fokus_pinggang', 'label' => 'Fokus pinggang'],
                    ['value' => 'fokus_paha', 'label' => 'Fokus paha'],
                    ['value' => 'fokus_lengan', 'label' => 'Fokus lengan'],
                    ['value' => 'selulit', 'label' => 'Perawatan selulit'],
                    ['value' => 'stretch_mark', 'label' => 'Perawatan stretch mark'],
                    ['value' => 'perawatan_kulit', 'label' => 'Perawatan kulit'],
                    ['value' => 'lulur', 'label' => 'Lulur / body scrub'],
                    ['value' => 'massage', 'label' => 'Massage'],
                    ['value' => 'pijat_limfatik', 'label' => 'Pijat limfatik'],
                    ['value' => 'breastcare', 'label' => 'Breastcare / pijat laktasi'],
                    ['value' => 'baby_massage', 'label' => 'Baby massage'],
                    ['value' => 'lainnya', 'label' => 'Lainnya'],
                ], 'others_textarea' => ['key' => 'treatment_goals_other', 'when' => 'lainnya']],
            ],
        ],

        [
            'key' => 'asi_breastcare',
            'title' => 'ASI & Breastcare',
            'fields' => [
                ['key' => 'asi_condition', 'label' => 'Kondisi ASI', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'lancar', 'label' => 'ASI lancar'],
                    ['value' => 'kurang_lancar', 'label' => 'ASI kurang lancar'],
                    ['value' => 'penuh_keras', 'label' => 'Payudara terasa penuh/keras'],
                    ['value' => 'tidak_nyaman', 'label' => 'Ada area yang terasa tidak nyaman'],
                    ['value' => 'belum_lancar', 'label' => 'ASI belum lancar'],
                    ['value' => 'tidak_keluhan', 'label' => 'Tidak memiliki keluhan ASI'],
                ], 'warning' => true],
                ['key' => 'asi_pattern', 'label' => 'Pola pemberian ASI', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'dbf', 'label' => 'DBF'],
                    ['value' => 'pumping', 'label' => 'Pumping'],
                    ['value' => 'dbf_pumping', 'label' => 'DBF + Pumping'],
                    ['value' => 'tidak_menyusui', 'label' => 'Tidak menyusui'],
                ]],
                ['key' => 'want_breastcare', 'label' => 'Apakah Anda ingin mendapatkan Breastcare / Pijat Laktasi?', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'ya', 'label' => 'Ya'],
                    ['value' => 'tidak', 'label' => 'Tidak, ASI sudah lancar'],
                    ['value' => 'konsultasi', 'label' => 'Ingin konsultasi terlebih dahulu'],
                ]],
            ],
        ],

        [
            'key' => 'tubuh_kulit',
            'title' => 'Tubuh & Kulit',
            'fields' => [
                ['key' => 'focus_areas', 'label' => 'Area tubuh yang ingin menjadi fokus', 'type' => 'checkbox', 'required' => false, 'options' => [
                    ['value' => 'perut', 'label' => 'Perut'],
                    ['value' => 'pinggang', 'label' => 'Pinggang'],
                    ['value' => 'paha', 'label' => 'Paha'],
                    ['value' => 'lengan', 'label' => 'Lengan'],
                    ['value' => 'punggung', 'label' => 'Punggung'],
                    ['value' => 'bokong', 'label' => 'Bokong'],
                    ['value' => 'kaki', 'label' => 'Kaki'],
                    ['value' => 'seluruh_tubuh', 'label' => 'Seluruh tubuh'],
                ]],
                ['key' => 'cellulite', 'label' => 'Selulit', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'tidak_ada', 'label' => 'Tidak ada'],
                    ['value' => 'ada', 'label' => 'Ada'],
                    ['value' => 'tidak_yakin', 'label' => 'Tidak yakin'],
                ]],
                ['key' => 'stretch_mark', 'label' => 'Stretch Mark', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'tidak_ada', 'label' => 'Tidak ada'],
                    ['value' => 'ada', 'label' => 'Ada'],
                    ['value' => 'tidak_yakin', 'label' => 'Tidak yakin'],
                ]],
                ['key' => 'stretch_mark_areas', 'label' => 'Area stretch mark', 'type' => 'checkbox', 'required' => false, 'options' => [
                    ['value' => 'perut', 'label' => 'Perut'],
                    ['value' => 'paha', 'label' => 'Paha'],
                    ['value' => 'pinggang', 'label' => 'Pinggang'],
                    ['value' => 'payudara', 'label' => 'Payudara'],
                    ['value' => 'bokong', 'label' => 'Bokong'],
                    ['value' => 'lengan', 'label' => 'Lengan'],
                    ['value' => 'lainnya', 'label' => 'Lainnya'],
                ], 'condition' => ['field' => 'stretch_mark', 'operator' => 'equals', 'value' => 'ada']],
                ['key' => 'skin_condition', 'label' => 'Kondisi kulit', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'normal', 'label' => 'Normal'],
                    ['value' => 'kering', 'label' => 'Kering'],
                    ['value' => 'sensitif', 'label' => 'Sensitif'],
                    ['value' => 'mudah_kemerahan', 'label' => 'Mudah kemerahan'],
                    ['value' => 'mudah_iritasi', 'label' => 'Mudah iritasi'],
                    ['value' => 'tidak_yakin', 'label' => 'Tidak yakin'],
                ]],
                ['key' => 'body_odor', 'label' => 'Apakah ada keluhan terkait aroma tubuh?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'tidak', 'label' => 'Tidak'],
                    ['value' => 'ada', 'label' => 'Ada'],
                    ['value' => 'ingin_perawatan', 'label' => 'Ingin mendapatkan perawatan tubuh'],
                ]],
                ['key' => 'want_lymphatic', 'label' => 'Apakah Anda tertarik dengan Pijat Limfatik?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'ya', 'label' => 'Ya'],
                    ['value' => 'tidak', 'label' => 'Tidak'],
                    ['value' => 'belum_tahu', 'label' => 'Belum tahu'],
                ]],
                ['key' => 'lymphatic_areas', 'label' => 'Area yang ingin menjadi fokus (Pijat Limfatik)', 'type' => 'checkbox', 'required' => false, 'options' => [
                    ['value' => 'perut', 'label' => 'Perut'],
                    ['value' => 'pinggang', 'label' => 'Pinggang'],
                    ['value' => 'paha', 'label' => 'Paha'],
                    ['value' => 'lengan', 'label' => 'Lengan'],
                    ['value' => 'kaki', 'label' => 'Kaki'],
                    ['value' => 'seluruh_tubuh', 'label' => 'Seluruh tubuh'],
                ], 'condition' => ['field' => 'want_lymphatic', 'operator' => 'equals', 'value' => 'ya']],
            ],
        ],

        [
            'key' => 'riwayat_kesehatan',
            'title' => 'Riwayat Kesehatan',
            'fields' => [
                ['key' => 'health_conditions', 'label' => 'Riwayat/kondisi kesehatan', 'type' => 'checkbox', 'required' => false, 'options' => [
                    ['value' => 'tidak_ada', 'label' => 'Tidak ada'],
                    ['value' => 'hipertensi', 'label' => 'Hipertensi / tekanan darah tinggi'],
                    ['value' => 'darah_rendah', 'label' => 'Tekanan darah rendah'],
                    ['value' => 'hb_rendah', 'label' => 'Hb rendah / anemia'],
                    ['value' => 'wasir', 'label' => 'Wasir'],
                    ['value' => 'diabetes', 'label' => 'Diabetes'],
                    ['value' => 'jantung', 'label' => 'Kondisi jantung'],
                    ['value' => 'operasi', 'label' => 'Riwayat operasi'],
                    ['value' => 'alergi', 'label' => 'Alergi'],
                    ['value' => 'lainnya', 'label' => 'Lainnya'],
                ], 'others_textarea' => ['key' => 'health_conditions_other', 'when' => 'lainnya']],
                ['key' => 'know_blood_pressure', 'label' => 'Apakah Anda mengetahui tekanan darah terakhir?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'ya', 'label' => 'Ya'],
                    ['value' => 'tidak', 'label' => 'Tidak'],
                ]],
                ['key' => 'systolic', 'label' => 'Tekanan darah sistolik', 'type' => 'number', 'required' => false, 'unit' => 'mmHg', 'condition' => ['field' => 'know_blood_pressure', 'operator' => 'equals', 'value' => 'ya']],
                ['key' => 'diastolic', 'label' => 'Tekanan darah diastolik', 'type' => 'number', 'required' => false, 'unit' => 'mmHg', 'condition' => ['field' => 'know_blood_pressure', 'operator' => 'equals', 'value' => 'ya']],
                ['key' => 'know_hb', 'label' => 'Apakah Anda mengetahui kadar Hb terakhir?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'ya', 'label' => 'Ya'],
                    ['value' => 'tidak', 'label' => 'Tidak'],
                ]],
                ['key' => 'hb_level', 'label' => 'Kadar Hb', 'type' => 'number', 'required' => false, 'unit' => 'g/dL', 'condition' => ['field' => 'know_hb', 'operator' => 'equals', 'value' => 'ya']],
                ['key' => 'diastasis_recti', 'label' => 'Apakah Anda pernah mendapatkan informasi/pemeriksaan mengenai Diastasis Recti (pemisahan otot perut setelah kehamilan)?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'ya_ada', 'label' => 'Ya, ada'],
                    ['value' => 'ya_membaik', 'label' => 'Ya, tetapi sudah membaik'],
                    ['value' => 'tidak_ada', 'label' => 'Tidak ada'],
                    ['value' => 'belum_diperiksa', 'label' => 'Belum pernah diperiksa'],
                    ['value' => 'tidak_tahu', 'label' => 'Tidak tahu'],
                ]],
            ],
        ],

        [
            'key' => 'perawatan_bayi',
            'title' => 'Perawatan Bayi',
            'fields' => [
                ['key' => 'want_baby_massage', 'label' => 'Apakah ingin menambahkan Baby Massage?', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'ya', 'label' => 'Ya'],
                    ['value' => 'tidak', 'label' => 'Tidak'],
                ]],
                ['key' => 'baby_age_months', 'label' => 'Usia bayi', 'type' => 'number', 'required' => false, 'unit' => 'bulan', 'condition' => ['field' => 'want_baby_massage', 'operator' => 'equals', 'value' => 'ya']],
                ['key' => 'baby_massage_goals', 'label' => 'Tujuan Baby Massage', 'type' => 'checkbox', 'required' => false, 'condition' => ['field' => 'want_baby_massage', 'operator' => 'equals', 'value' => 'ya'], 'options' => [
                    ['value' => 'relaksasi', 'label' => 'Relaksasi'],
                    ['value' => 'perawatan_rutin', 'label' => 'Perawatan rutin'],
                    ['value' => 'sulit_rileks', 'label' => 'Bayi sulit rileks'],
                    ['value' => 'lainnya', 'label' => 'Lainnya'],
                ]],
            ],
        ],

        [
            'key' => 'preferensi_treatment',
            'title' => 'Preferensi Treatment',
            'fields' => [
                ['key' => 'massage_pressure', 'label' => 'Tekanan massage yang Anda sukai', 'type' => 'radio', 'required' => true, 'options' => [
                    ['value' => 'lembut', 'label' => 'Lembut', 'description' => 'Untuk pengalaman yang lebih ringan dan relaksasi.'],
                    ['value' => 'sedang', 'label' => 'Sedang', 'description' => 'Tekanan seimbang dan nyaman.'],
                    ['value' => 'kuat', 'label' => 'Lebih kuat', 'description' => 'Untuk customer yang terbiasa dengan tekanan lebih kuat.'],
                ]],
                ['key' => 'support_after', 'label' => 'Apakah ingin menggunakan support setelah treatment?', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'gurita_perekat', 'label' => 'Gurita + perekat'],
                    ['value' => 'gurita_bengkung', 'label' => 'Gurita + bengkung'],
                    ['value' => 'korset_perekat', 'label' => 'Korset perekat saja'],
                    ['value' => 'tidak', 'label' => 'Tidak menggunakan'],
                    ['value' => 'konsultasi', 'label' => 'Konsultasi terlebih dahulu'],
                ]],
                ['key' => 'after_massage', 'label' => 'Setelah massage, Anda memilih', 'type' => 'radio', 'required' => false, 'options' => [
                    ['value' => 'uap', 'label' => 'Uap'],
                    ['value' => 'mandi', 'label' => 'Mandi'],
                    ['value' => 'uap_mandi', 'label' => 'Uap + mandi, jika direkomendasikan'],
                    ['value' => 'tidak', 'label' => 'Tidak memilih keduanya'],
                    ['value' => 'konsultasi', 'label' => 'Konsultasi terlebih dahulu'],
                ]],
            ],
        ],
    ],
];
