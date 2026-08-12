<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use App\Support\DummyImage;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@dianmustika.test')->first()
            ?? User::factory()->create(['name' => 'Admin Dian Mustika', 'email' => 'admin@dianmustika.test']);

        $articles = [
            [
                '7 Manfaat Pijat Relaksasi untuk Kesehatan Tubuh dan Pikiran',
                'perawatan-tubuh',
                'Pijat relaksasi bukan sekadar memanjakan diri. Banyak manfaat kesehatan yang bisa Anda dapatkan dari perawatan ini secara rutin.',
                'Pijat relaksasi sering dianggap sebagai bentuk memanjakan diri, padahal manfaatnya jauh lebih dalam dari sekadar rasa nyaman. Pijatan dengan teknik yang tepat dapat membantu tubuh dan pikiran kembali seimbang di tengah rutinitas yang padat.

<h2>Apa Itu Pijat Relaksasi?</h2>
Pijat relaksasi adalah terapi pijat dengan tekanan lembut hingga sedang yang bertujuan meredakan ketegangan otot dan menenangkan sistem saraf. Berbeda dengan deep tissue yang lebih fokus pada otot dalam, pijat relaksasi menekankan pada kenyamanan dan efek menenangkan secara menyeluruh.

<h2>Manfaat Pijat Relaksasi</h2>
Berikut beberapa manfaat yang bisa Anda rasakan:
<ul>
<li>Melancarkan peredaran darah sehingga tubuh terasa lebih segar</li>
<li>Meredakan ketegangan otot akibat duduk terlalu lama atau bekerja</li>
<li>Mengurangi stres dan kecemasan</li>
<li>Meningkatkan kualitas tidur</li>
<li>Membantu menurunkan tekanan darah</li>
<li>Meningkatkan fleksibilitas dan rentang gerak tubuh</li>
<li>Mempercepat pemulihan setelah aktivitas berat</li>
</ul>

<h2>Kapan Waktu yang Tepat?</h2>
Idealnya, pijat relaksasi dilakukan saat tubuh mulai terasa lelah atau setelah masa-masa penuh tekanan. Melakukan perawatan rutin satu hingga dua kali sebulan dapat membantu menjaga kondisi tubuh tetap prima.

<p>Konsultasikan kebutuhan Anda dengan terapis Dian Mustika untuk mendapatkan jenis pijat yang paling sesuai dengan kondisi tubuh Anda.</p>',
                true,
                1,
                'manfaat-pijat-relaksasi',
                now()->subDays(6),
            ],
            [
                'Cara Memilih Treatment Slimming yang Tepat untuk Anda',
                'slimming',
                'Tidak semua program slimming cocok untuk semua orang. Kenali kebutuhan tubuh Anda sebelum memilih perawatan.',
                'Program slimming semakin populer sebagai cara mendukung pembentukan tubuh ideal. Namun, memilih treatment yang tepat tidak bisa asal. Berikut hal yang perlu Anda perhatikan.

<h2>Kenali Kondisi Tubuh Anda</h2>
Sebelum memulai program slimming, kenali terlebih dahulu bagian tubuh mana yang ingin Anda targetkan. Beberapa program fokus pada area perut, lengan, paha, atau kontur tubuh bagian atas.

<h2>Perhatikan Metode yang Digunakan</h2>
Pilih perawatan yang menggunakan metode aman dan bahan yang sudah teruji. Body wrap dengan ramuan herbal, misalnya, bekerja dengan membantu mengencangkan kulit sekaligus mendukung proses pembentukan tubuh secara alami.

<h2>Konsistensi Lebih Penting dari Sekali Coba</h2>
Hasil terbaik tidak didapatkan dari satu sesi perawatan. Dibutuhkan program rutin serta diimbangi pola makan sehat dan olahraga ringan.

<h2>Konsultasikan dengan Terapis</h2>
Tim Dian Mustika siap membantu Anda menentukan program slimming yang sesuai dengan kebutuhan dan kondisi tubuh melalui konsultasi terlebih dahulu.',
                false,
                2,
                'memilih-treatment-slimming',
                now()->subDays(13),
            ],
            [
                'Rangkaian Perawatan Pasca Melahirkan yang Wajib Diketahui',
                'perawatan-tubuh',
                'Masa nifas membutuhkan perhatian khusus. Ketahui rangkaian perawatan yang membantu pemulihan ibu setelah melahirkan.',
                'Setelah melahirkan, tubuh ibu membutuhkan waktu untuk kembali pulih. Perawatan pasca melahirkan hadir untuk membantu proses pemulihan sekaligus memberikan rasa nyaman.

<h2>Mengapa Perawatan Pasca Melahirkan Penting?</h2>
Selama kehamilan dan persalinan, tubuh ibu mengalami banyak perubahan. Perawatan pasca melahirkan membantu melancarkan peredaran darah, meredakan pegal, dan mendukung pemulihan otot perut.

<h2>Jenis Perawatan yang Tersedia</h2>
<ul>
<li>Pijat pemulihan untuk meredakan pegal dan melancarkan peredaran darah</li>
<li>Perawatan perut untuk membantu pemulihan otot</li>
<li>Perawatan payudara untuk mendukung kelancaran ASI</li>
<li>Perawatan dengan ramuan tradisional untuk kenyamanan masa nifas</li>
</ul>

<h2>Kapan Boleh Memulai?</h2>
Umumnya perawatan dapat dimulai setelah kondisi ibu dinyatakan cukup stabil. Konsultasikan dengan terapis dan tenaga medis Anda sebelum memulai rangkaian perawatan.

<p>Dian Mustika memiliki terapis berpengalaman khusus perawatan pasca melahirkan yang dapat datang ke rumah Anda melalui layanan homecare.</p>',
                true,
                3,
                'perawatan-pasca-melahirkan',
                now()->subDays(20),
            ],
            [
                'Manfaat Lulur Rempah untuk Kulit Sehat dan Halus',
                'kecantikan',
                'Lulur rempah tradisional membantu mengangkat sel kulit mati dan menutrisi kulit secara alami.',
                'Lulur rempah merupakan salah satu perawatan tradisional yang telah digunakan sejak lama untuk menjaga kesehatan kulit. Kandungan rempah alami membantu merawat kulit dari luar.

<h2>Kandungan yang Bermanfaat</h2>
Campuran rempah seperti kunyit, beras, dan bahan alami lainnya membantu mengangkat sel kulit mati serta memberikan nutrisi pada kulit.

<h2>Manfaat untuk Kulit</h2>
<ul>
<li>Mengangkat sel kulit mati sehingga kulit terlihat lebih cerah</li>
<li>Melembutkan dan menghaluskan permukaan kulit</li>
<li>Menutrisi kulit secara alami</li>
<li>Memberikan efek relaksasi melalui aroma rempah</li>
</ul>

<h2>Tips Setelah Lulur</h2>
Setelah melakukan lulur, gunakan pelembap untuk menjaga kelembapan kulit dan hindari paparan matahari berlebihan pada hari yang sama.

<p>Rutin melakukan lulur rempah satu hingga dua kali seminggu dapat membantu menjaga kesehatan kulit tubuh Anda.</p>',
                false,
                4,
                'manfaat-lulur-rempah',
                now()->subDays(27),
            ],
            [
                'Totok Wajah: Rahasia Wajah Segar Tanpa Ribet',
                'kecantikan',
                'Teknik tradisional ini membantu melancarkan peredaran darah di wajah sehingga wajah tampak lebih segar.',
                'Totok wajah adalah teknik pijat wajah tradisional yang menstimulasi titik-titik saraf di wajah. Perawatan ini semakin populer karena praktis dan memberikan efek segar yang langsung terasa.

<h2>Apa yang Terjadi Saat Totok Wajah?</h2>
Terapis memberikan tekanan lembut pada titik-titik tertentu di wajah. Tekanan ini membantu melancarkan peredaran darah dan meredakan ketegangan otot wajah.

<h2>Manfaat Totok Wajah</h2>
<ul>
<li>Wajah terasa lebih segar dan rileks</li>
<li>Membantu mengencangkan kulit wajah</li>
<li>Melancarkan peredaran darah di area wajah</li>
<li>Mengurangi rasa tegang di kepala dan leher</li>
</ul>

<h2>Kapan Hasilnya Terlihat?</h2>
Efek segar dapat langsung terasa setelah satu sesi. Untuk hasil yang lebih optimal, lakukan totok wajah secara rutin sesuai saran terapis.',
                false,
                5,
                'totok-wajah-segar',
                now()->subDays(34),
            ],
            [
                '5 Kebiasaan Pagi untuk Kulit Glowing Sepanjang Hari',
                'lifestyle',
                'Mulai hari Anda dengan kebiasaan sederhana yang membuat kulit tampak sehat dan bercahaya.',
                'Kulit yang sehat tidak hanya ditentukan oleh produk perawatan, tetapi juga kebiasaan sehari-hari. Berikut lima kebiasaan pagi yang bisa Anda terapkan.

<h2>1. Minum Air Putih</h2>
Segera setelah bangun, minumlah segelas air putih untuk membantu hidrasi tubuh dan kulit dari dalam.

<h2>2. Bersihkan Wajah dengan Lembut</h2>
Bersihkan wajah dengan pembersih yang sesuai jenis kulit untuk menghilangkan sisa kotoran semalaman.

<h2>3. Gunakan Pelembap dan Sunscreen</h2>
Jangan lewatkan pelembap dan tabir surya sebelum beraktivitas. Perlindungan dari sinar matahari sangat penting untuk kesehatan kulit.

<h2>4. Sarapan Sehat</h2>
Konsumsi makanan kaya antioksidan seperti buah dan sayur untuk mendukung kesehatan kulit dari dalam.

<h2>5. Jangan Lupa Bergerak</h2>
Aktivitas ringan di pagi hari membantu melancarkan peredaran darah sehingga kulit terlihat lebih cerah.',
                false,
                6,
                'kebiasaan-pagi-kulit-glowing',
                now()->subDays(40),
            ],
            [
                'Kenali Perbedaan Massage Tradisional dan Deep Tissue',
                'perawatan-tubuh',
                'Kedua jenis pijat ini sama-sama menyehatkan, namun memiliki tujuan dan teknik yang berbeda.',
                'Banyak orang bingung memilih antara massage tradisional dan deep tissue. Keduanya memiliki manfaat, tetapi ditujukan untuk kebutuhan yang berbeda.

<h2>Massage Tradisional</h2>
Massage tradisional menggunakan tekanan lembut hingga sedang dengan teknik khas Indonesia. Perawatan ini cocok untuk relaksasi, meredakan pegal ringan, dan menjaga kebugaran tubuh.

<h2>Deep Tissue Massage</h2>
Deep tissue menggunakan tekanan lebih kuat dan gerakan lebih lambat untuk menjangkau lapisan otot yang dalam. Perawatan ini ditujukan untuk mengatasi ketegangan otot kronis, nyeri punggung, dan masalah postur.

<h2>Bagaimana Memilih?</h2>
Jika tujuan Anda adalah relaksasi dan melepas penat, massage tradisional sudah cukup. Namun, jika Anda memiliki keluhan otot yang lebih serius, deep tissue lebih direkomendasikan.

<p>Konsultasikan dengan terapis Dian Mustika untuk mendapatkan saran yang paling sesuai dengan kondisi Anda.</p>',
                true,
                7,
                'perbedaan-massage-tradisional-deep-tissue',
                now()->subDays(48),
            ],
            [
                'Apa Itu Herbal Bath dan Apa Saja Manfaatnya?',
                'perawatan-tubuh',
                'Berendam dengan ramuan herbal hangat memberikan efek relaksasi sekaligus manfaat bagi tubuh.',
                'Herbal bath adalah perawatan berendam menggunakan air hangat yang dicampur rempah-rempah pilihan. Tradisi ini sudah lama dikenal dan terus digemari karena memberikan banyak manfaat.

<h2>Proses Herbal Bath</h2>
Tubuh direndam dalam air hangat yang dicampur rempah seperti jahe dan serai. Suhu hangat membantu melemaskan otot dan melancarkan peredaran darah.

<h2>Manfaat Herbal Bath</h2>
<ul>
<li>Melancarkan peredaran darah</li>
<li>Melemaskan otot yang tegang</li>
<li>Mengurangi pegal dan linu</li>
<li>Memberikan efek relaksasi menyeluruh</li>
<li>Membantu tubuh terasa lebih segar</li>
</ul>

<h2>Siapa yang Cocok?</h2>
Herbal bath cocok untuk siapa saja yang membutuhkan relaksasi setelah aktivitas padat, termasuk ibu pasca melahirkan dengan kondisi yang sudah diizinkan.',
                false,
                8,
                'apa-itu-herbal-bath',
                now()->subDays(55),
            ],
            [
                'Panduan Merawat Tubuh di Rumah agar Tetap Bugar',
                'tips-kesehatan',
                'Perawatan di rumah yang sederhana bisa membantu tubuh tetap terasa segar di sela kesibukan.',
                'Tidak selalu sempat datang ke tempat perawatan. Beberapa kebiasaan sederhana di rumah bisa membantu tubuh tetap terasa segar dan bugar.

<h2>Lakukan Peregangan Ringan</h2>
Luangkan beberapa menit setiap pagi untuk meregangkan tubuh. Peregangan membantu melancarkan peredaran darah dan mengurangi kekakuan otot.

<h2>Perhatikan Posisi Duduk</h2>
Bagi Anda yang bekerja di depan komputer, perhatikan posisi duduk agar tidak menyebabkan pegal di punggung dan leher.

<h2>Gunakan Ramuan Alami</h2>
Sesekali gunakan lulur atau scrub alami di rumah untuk mengangkat sel kulit mati dan menjaga kulit tetap halus.

<h2>Jangan Lupa Istirahat</h2>
Tidur cukup adalah bagian penting dari perawatan tubuh. Usahakan tidur 7-8 jam setiap malam.

<p>Namun, untuk perawatan yang lebih mendalam, Anda bisa menghubungi Dian Mustika untuk layanan homecare yang datang langsung ke rumah Anda.</p>',
                false,
                9,
                'merawat-tubuh-di-rumah',
                now()->subDays(62),
            ],
        ];

        foreach ($articles as [$title, $categorySlug, $excerpt, $content, $featured, $order, $seed, $publishedAt]) {
            $category = ArticleCategory::where('slug', $categorySlug)->first();

            Article::updateOrCreate(
                ['slug' => str()->slug($title)],
                [
                    'article_category_id' => $category?->id,
                    'author_id' => $author->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'featured_image' => DummyImage::store('articles', $seed, 900, 600),
                    'alt_text' => $title,
                    'is_featured' => $featured,
                    'is_active' => true,
                    'published_at' => $publishedAt,
                ]
            );
        }
    }
}
