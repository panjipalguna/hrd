<aside>
   <div class="top">
     <div class="logo">
       <img src="assets/images/logo stikom.png" />
       <h2 cla>ITB <span class="primary">STIKOM Bali</span></h2>
     </div>
     <div class="close" id="close-btn">
       <span class="material-icons-sharp">close</span>
     </div>
   </div>

   <div class="sidebar">
     <a href="index.html">
       <span class="material-icons-sharp">grid_view</span>
       <h3>Dashboard</h3>
     </a>

     <a href="{{route('karyawan.index')}}" class="active">
       <span class="material-icons-sharp">text_snippet</span>
       <h3>Data Karyawan</h3>
     </a>

     <div class="dropdown">
       <a href="#" class="dropdown-btn">
         <h3>Lihat Data HRD</h3>
         <span class="material-icons-sharp">expand_circle_down</span>
       </a>
       <div class="dropdown-container">
         <a href="{{route('dapartement.index')}}">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Data Departmen</h3>
         </a>
         <a href="#">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Data Jabatan</h3>
         </a>
         <a href="{{route('jamKerja.index')}}">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Data Jam Kerja</h3>
         </a>
         <a href="{{route('cuti.index')}}">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Data Cuti</h3>
         </a>
         <a href="#">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Periode Gaji</h3>
         </a>
         <a href="#">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Daftar Gaji</h3>
         </a>
         <a href="#">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Pengumuman</h3>
         </a>
       </div>
     </div>

     <div class="dropdown">
       <a href="#" class="dropdown-btn">
         <h3>Data Kehadiran</h3>
         <span class="material-icons-sharp">expand_circle_down</span>
       </a>
       <div class="dropdown-container">
         <a href="#">
           <span class="material-icons-sharp">photo_camera</span>
           <h3>Data Absensi</h3>
         </a>
         <a href="#">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Data Lembur</h3>
         </a>
         <a href="#">
           <span class="material-icons-sharp">text_snippet</span>
           <h3>Data Lembur</h3>
         </a>
       </div>
     </div>

     <a href="#">
       <span class="material-icons-sharp">text_snippet</span>
       <h3>KPI (Indikator)</h3>
     </a>
     <a href="#">
       <span class="material-icons-sharp">text_snippet</span>
       <h3>KPA (Penilaian)</h3>
     </a>
     <a href="#">
       <span class="material-icons-sharp">text_snippet</span>
       <h3>Set Kompetensi</h3>
     </a>
     <a href="#">
       <span class="material-icons-sharp">settings</span>
       <h3>Profile</h3>
     </a>
     <a href="/login.html">
       <span class="material-icons-sharp">logout</span>
       <h3>Logout</h3>
     </a>
   </div>
</aside>
