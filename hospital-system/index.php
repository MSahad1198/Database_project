<?php include('includes/header.php'); ?>

<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 36px;
}
.dash-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 22px 20px 18px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
    border-top: 3px solid transparent;
    transition: transform .2s, box-shadow .2s, border-color .2s;
}
.dash-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--teal);
}
.dash-card.featured { border-top-color: var(--teal); background: var(--sky); }
.dash-card .icon { font-size: 1.8rem; line-height: 1; }
.dash-card .label { font-size: .85rem; font-weight: 600; color: var(--navy); }
.dash-card .sub   { font-size: .75rem; color: var(--grey-text); }
.section-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--navy);
    margin: 32px 0 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-title::after { content:''; flex:1; height:1px; background:var(--grey-mid); }
.hero {
    background: linear-gradient(135deg, var(--navy) 0%, #163a5f 100%);
    border-radius: var(--radius);
    padding: 36px 40px;
    margin-bottom: 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '+';
    position: absolute; right: -10px; top: -40px;
    font-size: 220px; font-weight: 700;
    color: rgba(0,194,179,.07); line-height: 1; pointer-events: none;
}
.hero-text h2 { color:var(--white); border:none; padding:0; margin:0 0 8px; font-size:2.2rem; }
.hero-text p  { color:var(--grey-mid); font-size:.9rem; max-width:380px; }
.hero-badge {
    background: rgba(0,194,179,.15);
    border: 1px solid rgba(0,194,179,.3);
    border-radius: 50px;
    padding: 8px 20px;
    color: var(--teal);
    font-size: .8rem; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
}
</style>

<div class="hero">
    <div class="hero-text">
        <h2>Welcome Back</h2>
        <p>Manage patients, appointments, pharmacy and billing from one place.</p>
    </div>
    <div class="hero-badge">Hospital Admin</div>
</div>

<div class="section-title">Modules</div>
<div class="dashboard-grid">
    <a class="dash-card" href="patients/list.php">
        <div class="icon">🧑‍⚕️</div><div class="label">Patients</div><div class="sub">View &amp; add patients</div>
    </a>
    <a class="dash-card" href="doctors/list.php">
        <div class="icon">👨‍⚕️</div><div class="label">Doctors</div><div class="sub">Manage doctors</div>
    </a>
    <a class="dash-card" href="specializations/list.php">
        <div class="icon">🏥</div><div class="label">Specializations</div><div class="sub">Medical departments</div>
    </a>
    <a class="dash-card" href="appointments/list.php">
        <div class="icon">📅</div><div class="label">Appointments</div><div class="sub">Book &amp; track visits</div>
    </a>
    <a class="dash-card" href="bills/list.php">
        <div class="icon">💳</div><div class="label">Bills</div><div class="sub">Patient payments</div>
    </a>
    <a class="dash-card" href="medications/list.php">
        <div class="icon">💊</div><div class="label">Medications</div><div class="sub">Drug catalogue</div>
    </a>
    <a class="dash-card" href="treatments/list.php">
        <div class="icon">🩺</div><div class="label">Treatments</div><div class="sub">Treatment records</div>
    </a>
    <a class="dash-card" href="prescriptions/list.php">
        <div class="icon">📋</div><div class="label">Prescriptions</div><div class="sub">Medicine orders</div>
    </a>
    <a class="dash-card" href="pharmacy/list.php">
        <div class="icon">🏪</div><div class="label">Pharmacy</div><div class="sub">Medicine sales</div>
    </a>
</div>

<div class="section-title">Reports</div>
<div class="dashboard-grid">
    <a class="dash-card featured" href="reports/patient_history.php">
        <div class="icon">🗂️</div><div class="label">Patient Full History</div><div class="sub">JOIN + UNION timeline</div>
    </a>
    <a class="dash-card" href="reports/report1_full_join.php">
        <div class="icon">📊</div><div class="label">Full Join Report</div><div class="sub">All 7 tables joined</div>
    </a>
    <a class="dash-card" href="reports/report2_aggregation.php">
        <div class="icon">📈</div><div class="label">Aggregation</div><div class="sub">Doctor appointment counts</div>
    </a>
    <a class="dash-card" href="reports/report3_having.php">
        <div class="icon">💰</div><div class="label">Having</div><div class="sub">High spending patients</div>
    </a>
    <a class="dash-card" href="reports/report4_filter.php">
        <div class="icon">🔍</div><div class="label">Filter</div><div class="sub">Expensive medications</div>
    </a>
    <a class="dash-card" href="reports/report5_leftjoin.php">
        <div class="icon">🔗</div><div class="label">Left Join</div><div class="sub">Doctors per specialization</div>
    </a>
    <a class="dash-card" href="reports/report6_top.php">
        <div class="icon">🏆</div><div class="label">Top Patients</div><div class="sub">Highest 5 bills</div>
    </a>
    <a class="dash-card" href="reports/report7_subquery.php">
        <div class="icon">🔎</div><div class="label">Subquery</div><div class="sub">Bills over 3,000</div>
    </a>
</div>

<?php include('includes/footer.php'); ?>