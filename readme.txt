一、系統核心功能：
會員帳戶管理 (Account Management)：

具備註冊與登入功能，並劃分 管理員 (Admin) 與 一般會員 (Member) 角色。

管理員可進入後台，針對會員的「所屬群組 (Group)」與「權限等級」進行即時修改。

動態表單建立 (Dynamic Form Builder)：

會員可自主發起揪團活動，並透過 JavaScript/jQuery 實現巢狀動態生成：

題目管理：動態新增或刪除問題。

多樣題型：支援簡答、詳答、單選、多選。

巢狀選項：單選與多選題可即時增減選項欄位。

報名與自動表單渲染 (Form Rendering & Response)：

系統根據資料庫結構，自動將表單題目渲染為對應的 HTML 欄位（Text, Radio, Checkbox）。

使用者提交後，系統自動將多選答案轉置（Data Pivoting）並存儲於關聯表中。

數據圖表分析 (Data Visualization)：

提供發起人即時的報名結果概況，使用 Bootstrap Progress Bar 進行 視覺化百分比統計。

展示各選項的票數分佈，讓數據一目了然。

系統公告欄 (Announcement System)：

首頁具備全局公告區塊，由管理員於後台發布重要訊息（如系統維護、活動推薦）。

訪客與會員皆可查看，管理員具備發布與刪除之管理權限。

個人表單管理面版 (User Dashboard)：

提供「我的表單」頁面，讓使用者追蹤自己發起的活動，並具備查看報名結果與連鎖刪除（Cascade Delete）功能。

二、技術規格重點：
後端架構：PHP 8.x + MySQL (MariaDB)。

前端技術：Bootstrap 5 (RWD 響應式設計)、JavaScript (jQuery)、Bootstrap Icons。

資料庫設計：符合三範式 (3NF) 邏輯，並應用 ON DELETE CASCADE 維護資料完整性。

安全機制：

防止自我鎖定 (Self-Lockout Prevention) 的權限檢查。

mysqli_real_escape_string 防範 SQL 注入攻擊。

Session 權限控管 (包含一般頁面與後台管理區分)。