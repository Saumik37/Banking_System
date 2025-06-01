<?php

if (!isset($con) || !$con) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where_conditions = [];
$search_firstname = $_POST['search_firstname'] ?? '';
$search_lastname = $_POST['search_lastname'] ?? '';
$search_email = $_POST['search_email'] ?? '';
$search_gender = $_POST['search_gender'] ?? '';

if (!empty($search_firstname)) {
    $where_conditions[] = "firstname LIKE '%" . mysqli_real_escape_string($con, $search_firstname) . "%'";
}
if (!empty($search_lastname)) {
    $where_conditions[] = "lastname LIKE '%" . mysqli_real_escape_string($con, $search_lastname) . "%'";
}
if (!empty($search_email)) {
    $where_conditions[] = "email LIKE '%" . mysqli_real_escape_string($con, $search_email) . "%'";
}
if (!empty($search_gender)) {
    $where_conditions[] = "gender = '" . mysqli_real_escape_string($con, $search_gender) . "'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$count_sql = "SELECT COUNT(*) as total FROM User_Table $where_clause";
$count_result = mysqli_query($con, $count_sql);

if (!$count_result) {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . mysqli_error($con)]);
    exit;
}

$total_users = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_users / $limit);

$sql = "SELECT * FROM User_Table $where_clause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . mysqli_error($con)]);
    exit;
}

$table_html = '';
while ($row = mysqli_fetch_assoc($result)) {
    $table_html .= '<tr>';
    $table_html .= '<td>' . $row['id'] . '</td>';
    $table_html .= '<td>' . htmlspecialchars($row['firstname']) . '</td>';
    $table_html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
    $table_html .= '<td>' . htmlspecialchars($row['nid']) . '</td>';
    $table_html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
    $table_html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
    $table_html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
    $table_html .= '<td>';
    $table_html .= '<button class="btn btn-sm btn-primary" onclick="editUser(' . $row['id'] . ')">Edit</button>';
    $table_html .= '<button class="btn btn-sm btn-danger" onclick="deleteUser(' . $row['id'] . ')">Delete</button>';
    $table_html .= '</td>';
    $table_html .= '</tr>';
}

$pagination_html = '';

if ($page > 1) {
    $pagination_html .= '<button class="btn btn-secondary" onclick="loadPage(' . ($page - 1) . ')">Previous</button>';
}

$pagination_html .= '<span class="page-info">Page ' . $page . ' of ' . $total_pages . ' (' . $total_users . ' total users)</span>';

if ($page < $total_pages) {
    $pagination_html .= '<button class="btn btn-secondary" onclick="loadPage(' . ($page + 1) . ')">Next</button>';
}

echo json_encode([
    'success' => true,
    'table_html' => $table_html,
    'pagination_html' => $pagination_html,
    'total_users' => $total_users,
    'current_page' => $page,
    'total_pages' => $total_pages
]);
?>